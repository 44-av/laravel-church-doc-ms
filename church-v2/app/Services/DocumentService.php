<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Models\Document;
use App\Models\Notification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\Element\Text;

class DocumentService
{
    private $validator;

    public function __construct(useValidator $validator)
    {
        $this->validator = $validator;
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), $this->validator->documentValidator());

        if ($validator->fails()) {
            session()->flash('error', $validator->errors()->first());
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $validator->errors()->first(),
            ];
        }

        try {
            $document = new Document();

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $ocrText = $this->extractTextFromFile($file);

                if (empty(trim($ocrText))) {
                    session()->flash('error', 'Failed to upload document or image is blurry, please try again.');
                    return [
                        'error_code' => MyConstant::FAILED_CODE,
                        'status_code' => MyConstant::BAD_REQUEST,
                        'message' => 'Failed to upload document or image is blurry, please try again.',
                    ];
                }

                if ($this->checkForInappropriateContent($ocrText)) {
                    session()->flash('error', 'Inappropriate content detected. Upload rejected.');
                    return [
                        'error_code' => MyConstant::FAILED_CODE,
                        'status_code' => MyConstant::BAD_REQUEST,
                        'message' => 'Inappropriate content detected. Upload rejected.',
                    ];
                }

                $documentType = $this->determineDocumentType($ocrText);

                if ($documentType === 'Verification Certificate') {
                    session()->flash('error', 'Failed to upload document or image is blurry, please try again.');
                    return [
                        'error_code' => MyConstant::FAILED_CODE,
                        'status_code' => MyConstant::BAD_REQUEST,
                        'message' => 'Failed to upload document or image is blurry, please try again.',
                    ];
                }

                $directory = public_path('assets/documents/' . $documentType);

                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $fileName = basename($file->getClientOriginalName());
                $file->move($directory, $fileName);
                $document->document_type = $documentType;
                $document->file = $fileName;
            }

            $document->full_name = $request->full_name;
            $document->uploaded_by = $request->uploaded_by;
            $document->save();

            $notification = new Notification();
            $notification->type = 'Document';
            $notification->message = 'A new document has been uploaded by ' . Auth::user()->name;
            $notification->is_read = '0';
            $notification->save();

            session()->flash('success', 'Document created successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Document created successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error',
            ];
        }
    }

    public function update($request, $id)
    {
        $validator = Validator::make($request->all(), $this->validator->documentValidator());

        if ($validator->fails()) {
            session()->flash('error', $validator->errors()->first());
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $validator->errors()->first(),
            ];
        }

        try {
            $document = Document::find($id);

            if (!$document) {
                session()->flash('error', 'Document not found');
                return [
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::NOT_FOUND,
                    'message' => 'Document not found',
                ];
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $ocrText = $this->extractTextFromFile($file);

                if (empty(trim($ocrText))) {
                    session()->flash('error', 'Failed to upload document or image is blurry, please try again.');
                    return [
                        'error_code' => MyConstant::FAILED_CODE,
                        'status_code' => MyConstant::BAD_REQUEST,
                        'message' => 'Failed to upload document or image is blurry, please try again.',
                    ];
                }

                if ($this->checkForInappropriateContent($ocrText)) {
                    session()->flash('error', 'Inappropriate content detected. Update rejected.');
                    return [
                        'error_code' => MyConstant::FAILED_CODE,
                        'status_code' => MyConstant::BAD_REQUEST,
                        'message' => 'Inappropriate content detected. Update rejected.',
                    ];
                }

                $documentType = $this->determineDocumentType($ocrText);

                if ($documentType === 'Verification Certificate') {
                    session()->flash('error', 'Failed to upload document or image is blurry, please try again.');
                    return [
                        'error_code' => MyConstant::FAILED_CODE,
                        'status_code' => MyConstant::BAD_REQUEST,
                        'message' => 'Failed to upload document or image is blurry, please try again.',
                    ];
                }

                $fileName = basename($file->getClientOriginalName());

                if (file_exists(public_path('assets/documents/' . $document->document_type . '/' . $document->file))) {
                    unlink(public_path('assets/documents/' . $document->document_type . '/' . $document->file));
                }

                $directory = public_path('assets/documents/' . $documentType);

                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $file->move($directory, $fileName);
                $document->file = $fileName;
                $document->document_type = $documentType;
            }

            $document->update($request->except('file'));

            session()->flash('success', 'Document updated successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Document updated successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error',
            ];
        }
    }

    private function extractTextFromFile($file)
    {
        $ocrText = '';
        $extension = $file->getClientOriginalExtension();

        if ($extension === 'pdf') {
            $ocrText = $this->convertPdfToText($file->getPathname());
        } elseif ($extension === 'docx') {
            $ocrText = $this->convertDocxToText($file->getPathname());
        } else {
            $outPath = public_path('assets/documents/out_' . time());
            $ocrText = $this->processImageWithTesseract($file->getPathname(), $outPath);
        }

        return $ocrText;
    }

    private function checkForInappropriateContent($text)
    {
        $pythonScriptPath = base_path('python/svm_model.py');
        $command = escapeshellcmd("python $pythonScriptPath \"$text\"");

        $output = shell_exec($command);

        return trim($output) === 'inappropriate';
    }

    private function determineDocumentType($ocrText)
    {
        $ocrText = strtolower(trim($ocrText));
        $ocrText = preg_replace('/\s+/', ' ', $ocrText);

        $documentTypes = [
            'Baptismal' => ['baptism', 'baptist', 'baptismal'],
            'Marriage' => ['marriage', 'married'],
            'Death' => ['death', 'burial', 'deceased'],
            'Confirmation' => ['confirm', 'confirmation'],
            'Verification' => ['verification', 'verification certificate'],
        ];

        foreach ($documentTypes as $type => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($ocrText, $keyword) !== false) {
                    return ucfirst($type) . ' Certificate';
                }
            }
        }

        return 'Verification Certificate';
    }

    private function processImageWithTesseract($imagePath, $outPath)
    {
        $tesseractPath = 'C:\Program Files\Tesseract-OCR\tesseract.exe';
        $command = "\"$tesseractPath\" \"$imagePath\" \"$outPath\"";
        shell_exec($command);

        $outputFilePath = $outPath . '.txt';
        if (file_exists($outputFilePath)) {
            $text = file_get_contents($outputFilePath);
            return $text;
        }
        return '';
    }

    private function convertPdfToText($pdfPath)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();
        return $text;
    }

    private function convertDocxToText($docxPath)
    {
        $phpWord = IOFactory::load($docxPath);
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($element->getElements() as $textElement) {
                        if ($textElement instanceof Text) {
                            $text .= $textElement->getText() . "\n";
                        }
                    }
                }
            }
        }
        return $text;
    }

    // Restore data
    public function restoreDocument($id)
    {
        // Assuming your model is called Document
        $document = Document::withTrashed()->find($id);

        if ($document) {
            $document->restore();
            return true; // Success
        }

        return false; // Failure
    }



    public function destroy($id)
    {
        try {
            $document = Document::find($id);

            if (!$document) {
                return [
                    'error_code' => MyConstant::FAILED_CODE,
                    'status_code' => MyConstant::NOT_FOUND,
                    'message' => 'Document not found',
                ];
            }

            if (file_exists(public_path('assets/documents/' . $document->file))) {
                unlink(public_path('assets/documents/' . $document->file));
            }

            $oldOutPath = public_path('assets/documents/out_' . pathinfo($document->file, PATHINFO_FILENAME));
            if (file_exists($oldOutPath . '.txt')) {
                unlink($oldOutPath . '.txt');
            }

            $document->delete();

            session()->flash('success', 'Document deleted successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Document deleted successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error',
            ];
        }
    }
}
