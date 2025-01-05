<section class="space-y-6 p-5">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Recently Deleted Requests') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('These are the recently deleted requests. You can review them and take further actions if needed.') }}
        </p>
        <p class="mt-1 text-sm text-gray-600 font-semibold">
            {{-- {{ __('Total Deleted Files: ') }} <span class="text-blue-600">{{ $deletedCount ?? 0 }}</span> --}}
            {{ __('Total Deleted Files: ') }} <span class="text-blue-600">{{ 0 }}</span>

        </p>
    </header>

    <x-danger-button x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('View Deleted Requests') }}</x-danger-button>
</section>
