<?php

namespace App\Services;

use App\Constant\MyConstant;
use App\Jobs\BurialJob;
use App\Models\CertificateDetail;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Request as RequestModel;
use App\Services\useValidator;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;

class RequestService
{
    private $validator;

    public function __construct(useValidator $validator)
    {
        $this->validator = $validator;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validator->requestValidator());

        if ($validator->fails()) {
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $validator->errors()->first(),
            ];
        }

        try {
            RequestModel::create([
                'requested_by' => Auth::user()->id,
                'document_type' => $request->document_type,
                'status' => 'Pending',
                'is_paid' => 'Unpaid',
                'is_deleted' => '0',
            ]);

            if ($request->document_type == 'Baptismal Certificate') {
                CertificateDetail::create([
                    'certificate_type' => $request->document_type,
                    'name_of_child' => $request->name_of_child,
                    'date_of_birth' => $request->date_of_birth,
                    'place_of_birth' => $request->place_of_birth,
                    'name_of_father' => $request->name_of_father,
                    'place_of_birth_father' => $request->place_of_birth_father,
                    'name_of_mother' => $request->name_of_mother,
                    'place_of_birth_mother' => $request->place_of_birth_mother,
                    'present_residence' => $request->present_residence,
                    'sponsors' => $request->sponsors,
                    'contact_number' => $request->contact_number,
                    'baptism_schedule' => $request->baptism_schedule,
                    'pickup_date' => $request->pickup_date,
                    'minister_baptism' => $request->minister_baptism,
                ]);
            } elseif ($request->document_type == 'Marriage Certificate') {
                CertificateDetail::create([
                    'certificate_type' => $request->document_type,
                    'bride_name' => $request->bride_name,
                    'age_bride' => $request->age_bride,
                    'birthdate_bride' => $request->birthdate_bride,
                    'birthplace_bride' => $request->birthplace_bride,
                    'citizenship_bride' => $request->citizenship_bride,
                    'religion_bride' => $request->religion_bride,
                    'residence_bride' => $request->residence_bride,
                    'civil_status_bride' => $request->civil_status_bride,
                    'name_of_father_bride' => $request->name_of_father_bride,
                    'name_of_mother_bride' => $request->name_of_mother_bride,
                    'name_of_groom' => $request->name_of_groom,
                    'age_groom' => $request->age_groom,
                    'birthdate_groom' => $request->birthdate_groom,
                    'birthplace_groom' => $request->birthplace_groom,
                    'citizenship_groom' => $request->citizenship_groom,
                    'religion_groom' => $request->religion_groom,
                    'residence_groom' => $request->residence_groom,
                    'civil_status_groom' => $request->civil_status_groom,
                    'name_of_father_groom' => $request->name_of_father_groom,
                    'name_of_mother_groom' => $request->name_of_mother_groom,
                ]);
            } elseif ($request->document_type == 'Death Certificate') {
                if ($request->hasFile('file_death')) {
                    $file = $request->file('file_death');
                    $fileName = basename($file->getClientOriginalName());
                    $filePath = public_path('assets/deaths/' . $fileName);
                    $file->move(public_path('assets/deaths'), $fileName);
                }

                CertificateDetail::create([
                    'certificate_type' => $request->document_type,
                    'first_name_death' => $request->first_name_death,
                    'middle_name_death' => $request->middle_name_death,
                    'last_name_death' => $request->last_name_death,
                    'date_of_birth_death' => $request->date_of_birth_death,
                    'date_of_death' => $request->date_of_death,
                    'file_death' => $fileName,
                ]);
            } elseif ($request->document_type == 'Confirmation Certificate') {
                CertificateDetail::create([
                    'certificate_type' => $request->document_type,
                    'confirmation_first_name' => $request->confirmation_first_name,
                    'confirmation_middle_name' => $request->confirmation_middle_name,
                    'confirmation_last_name' => $request->confirmation_last_name,
                    'confirmation_place_of_birth' => $request->confirmation_place_of_birth,
                    'confirmation_date_of_baptism' => $request->confirmation_date_of_baptism,
                    'confirmation_fathers_name' => $request->confirmation_fathers_name,
                    'confirmation_mothers_name' => $request->confirmation_mothers_name,
                    'confirmation_date_of_confirmation' => $request->confirmation_date_of_confirmation,
                    'confirmation_sponsors_name' => $request->confirmation_sponsors_name,
                ]);
            }

            Notification::create([
                'type' => 'Request',
                'message' => 'A new request has been created by ' . Auth::user()->name,
                'is_read' => '0',
            ]);

            session()->flash('success', 'Request created successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Request created successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error: ' . $e->getMessage(),
            ];
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->validator->requestValidator());

        if ($validator->fails()) {
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $validator->errors()->first(),
            ];
        }

        try {
            RequestModel::where('id', $id)->update([
                'document_type' => $request->document_type,
                'requested_by' => Auth::user()->id,
                'status' => 'Pending',
                'is_paid' => 'Paid',
            ]);

            if ($request->document_type == 'Baptismal Certificate') {
                CertificateDetail::where('id', $id)->update([
                    'name_of_child' => $request->name_of_child,
                    'date_of_birth' => $request->date_of_birth,
                    'place_of_birth' => $request->place_of_birth,
                    'baptism_schedule' => $request->baptism_schedule,
                    'name_of_father' => $request->name_of_father,
                    'name_of_mother' => $request->name_of_mother,
                ]);
            } elseif ($request->document_type == 'Marriage Certificate') {
                CertificateDetail::where('id', $id)->update([
                    'bride_name' => $request->bride_name,
                    'age_bride' => $request->age_bride,
                    'birthdate_bride' => $request->birthdate_bride,
                    'birthplace_bride' => $request->birthplace_bride,
                    'citizenship_bride' => $request->citizenship_bride,
                    'religion_bride' => $request->religion_bride,
                    'residence_bride' => $request->residence_bride,
                    'civil_status_bride' => $request->civil_status_bride,
                    'name_of_father_bride' => $request->name_of_father_bride,
                    'name_of_mother_bride' => $request->name_of_mother_bride,
                    'name_of_groom' => $request->name_of_groom,
                    'age_groom' => $request->age_groom,
                    'birthdate_groom' => $request->birthdate_groom,
                    'birthplace_groom' => $request->birthplace_groom,
                    'citizenship_groom' => $request->citizenship_groom,
                    'religion_groom' => $request->religion_groom,
                    'residence_groom' => $request->residence_groom,
                    'civil_status_groom' => $request->civil_status_groom,
                    'name_of_father_groom' => $request->name_of_father_groom,
                    'name_of_mother_groom' => $request->name_of_mother_groom,
                ]);
            } elseif ($request->document_type == 'Death Certificate') {
                if ($request->hasFile('file_death')) {
                    $file = $request->file('file_death');
                    $fileName = basename($file->getClientOriginalName());
                    $file->move(public_path('assets/deaths'), $fileName);
                } else {
                    $existingCertificate = CertificateDetail::find($id);
                    if ($existingCertificate) {
                        $fileName = $existingCertificate->file_death;
                    }
                }

                CertificateDetail::where('id', $id)->update([
                    'first_name_death' => $request->first_name_death,
                    'middle_name_death' => $request->middle_name_death,
                    'last_name_death' => $request->last_name_death,
                    'file_death' => $fileName,
                ]);
            } elseif ($request->document_type == 'Confirmation Certificate') {
                CertificateDetail::where('id', $id)->update([
                    'confirmation_first_name' => $request->confirmation_first_name,
                    'confirmation_middle_name' => $request->confirmation_middle_name,
                    'confirmation_last_name' => $request->confirmation_last_name,
                    'confirmation_place_of_birth' => $request->confirmation_place_of_birth,
                    'confirmation_date_of_baptism' => $request->confirmation_date_of_baptism,
                    'confirmation_fathers_name' => $request->confirmation_fathers_name,
                    'confirmation_mothers_name' => $request->confirmation_mothers_name,
                    'confirmation_date_of_confirmation' => $request->confirmation_date_of_confirmation,
                    'confirmation_sponsors_name' => $request->confirmation_sponsors_name,
                ]);
            }

            if ($request->amout != null) {
                Payment::create([
                    'request_id' => $id,
                    'payment_status' => 'Paid',
                    'payment_method' => $request->payment_method,
                    'amount' => $request->amount,
                    'payment_date' => now('Asia/Manila'),
                    'transaction_id' => $request->transaction_id,
                ]);
            }

            if ($request->status == 'Decline') {
                Notification::create([
                    'type' => 'Payment',
                    'message' => 'A new request payment has been declined by ' . Auth::user()->name,
                    'is_read' => '0',
                ]);
            } else {
                Notification::create([
                    'type' => 'Request',
                    'message' => 'A new request has been updated by ' . Auth::user()->name,
                    'is_read' => '0',
                ]);
            }

            session()->flash('success', 'Request updated successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Request updated successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error: ' . $e->getMessage(),
            ];
        }
    }

    public function approve_request(Request $request, $id)
    {
        $validator = Validator::make($request->all(), $this->validator->requestValidator());

        if ($validator->fails()) {
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $validator->errors()->first(),
            ];
        }

        try {
            RequestModel::where('id', $id)->update([
                'approved_by' => Auth::user()->id,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            if ($request->is_paid == 'Paid') {
                Payment::where('request_id', $id)->update([
                    'name' => Auth::user()->name,
                    'payment_status' => 'Paid',
                    'payment_method' => 'Gcash',
                    'amount' => $request->amount,
                    'payment_date' => now('Asia/Manila'),
                    'transaction_id' => $request->transaction_id,
                ]);
            }

            if ($request->status == 'Decline') {
                RequestModel::where('id', $id)->update([
                    'status' => 'Decline',
                    'notes' => $request->notes,
                ]);

                $user = User::where('id', $request->requested_by)->first();
                if ($user) {
                    $email = $user->email;

                    $phpMailer = new PHPMailer(true);
                    $phpMailer->isSMTP();
                    $phpMailer->Host = 'smtp.gmail.com';
                    $phpMailer->SMTPAuth = true;
                    $phpMailer->Username = 'airisjane02@gmail.com';
                    $phpMailer->Password = 'llrj duoc aobd imyu';
                    $phpMailer->SMTPSecure = 'tls';
                    $phpMailer->Port = 587;

                    $phpMailer->setFrom('airisjane02@gmail.com', 'Airis Jane Baclea-an');
                    $phpMailer->addAddress($email, $user->name);
                    $phpMailer->Subject = 'Request Declined';
                    $phpMailer->Body = 'Your request has been declined. Notes: ' . $request->notes;

                    $phpMailer->send();
                }
            }

            if ($request->status == 'Decline') {
                Notification::create([
                    'type' => 'Request',
                    'message' => 'A new request has been declined by ' . Auth::user()->name,
                    'is_read' => '0',
                ]);
            } else {
                Notification::create([
                    'type' => 'Payment',
                    'message' => 'A new request payment has been made by ' . Auth::user()->name,
                    'is_read' => '0',
                ]);
            }

            session()->flash('success', 'Request updated successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Request updated successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error: ' . $e->getMessage(),
            ];
        }
    }

    public function destroy($id)
    {
        try {
            RequestModel::where('id', $id)->delete();

            session()->flash('success', 'Request deleted successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Request deleted successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error: ' . $e->getMessage(),
            ];
        }
    }


    // Dashboard Request Baptismal
    public function requestBaptismal(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validator->requestValidator());

        if ($validator->fails()) {
            return [
                'error_code' => MyConstant::FAILED_CODE,
                'status_code' => MyConstant::BAD_REQUEST,
                'message' => $validator->errors()->first(),
            ];
        }

        try {
            $content = $request->input('content');
            preg_match('/<img src="data:image\/(.*?);base64,(.*?)"/', $content, $matches);

            if (isset($matches[2])) {
                $imageData = base64_decode($matches[2]);
                $imageName = 'baptismal_certificate_' . time() . '.png';
                $imagePath = public_path('assets/documents/Baptismal_Certificate/' . $imageName);

                file_put_contents($imagePath, $imageData);
            } else {
                $imageName = null;
            }

            RequestModel::create([
                'requested_by' => Auth::user()->id,
                'document_type' => $request->document_type,
                'status' => 'Pending',
                'is_paid' => 'Unpaid',
                'file' => $imageName,
            ]);

            if ($request->document_type == 'Baptismal Certificate') {
                CertificateDetail::create([
                    'certificate_type' => $request->document_type,
                    'name_of_child' => $request->name_of_child,
                    'date_of_birth' => $request->date_of_birth,
                    'place_of_birth' => $request->place_of_birth,
                    'date_of_baptism' => now('Asia/Manila'),
                    'name_of_father' => $request->name_of_father,
                    'name_of_mother' => $request->name_of_mother,
                ]);
            }

            Notification::create([
                'type' => 'Request',
                'message' => 'A new baptismal certificate request has been created by ' . Auth::user()->name,
                'is_read' => '1',
            ]);

            session()->flash('success', 'Request created successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Request created successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error: ' . $e->getMessage(),
            ];
        }
    }

    public function updatePayment(Request $request, $id, $amount)
    {
        try {
            RequestModel::where('id', $id)->update([
                'is_paid' => 'Paid',
            ]);

            // Payment creation for all payment methods
                Payment::create([
                    'request_id' => $id,
                    'name' => Auth::user()->name,
                    'payment_status' => 'Paid',
                    'payment_method' => 'Gcash',
                    'amount' => $amount,
                    'payment_date' => now('Asia/Manila'),
                    'transaction_id' => $request->transaction_id,
                ]);
            if ($request->status == 'Approved') {
                Notification::create([
                    'type' => 'Request',
                    'message' => 'A request has been approved by ' . Auth::user()->name,
                    'is_read' => '0',
                ]);
            }
                Notification::create([
                    'type' => 'Payment',
                    'message' => 'A request payment has been made by ' . Auth::user()->name,
                    'is_read' => '0',
                ]);session()->flash('success', 'Request payment updated successfully');
            return [
                'error_code' => MyConstant::SUCCESS_CODE,
                'status_code' => MyConstant::OK,
                'message' => 'Request payment updated successfully',
            ];
        } catch (QueryException $e) {
            session()->flash('error', 'Internal server error');
            return [
                'error_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'status_code' => MyConstant::INTERNAL_SERVER_ERROR,
                'message' => 'Internal server error: ' . $e->getMessage(),
            ];
        }
    }
};