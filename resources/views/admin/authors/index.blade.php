@extends('layouts.cms')

@section('title', 'User & Author Management - University News')
@section('header_tagline', 'USER MANAGEMENT - CMS PORTAL')
@section('page_tour_id', 'admin.authors.index')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold font-heading text-[#00081e] tracking-tight">User &amp; Author Management</h1>
            <p class="text-gray-500 font-sans text-sm mt-1">Review author applications, manage contributors, and control permissions.</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Section 1: Pending Author Applications -->
    <div class="bg-white border border-gray-200 shadow-sm overflow-hidden" data-tour="authors-pending-section">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-yellow-50/50">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 {{ $pendingAuthors->count() > 0 ? 'animate-pulse' : '' }}"></span>
                <h2 class="text-base font-bold font-heading text-[#00081e]">Pending Author Applications ({{ $pendingAuthors->count() }})</h2>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-sans">
                <thead class="bg-[#f8f9fa] text-gray-500 uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 font-bold">Applicant</th>
                        <th class="px-6 py-3.5 font-bold">University &amp; Department</th>
                        <th class="px-6 py-3.5 font-bold">Applied</th>
                        <th class="px-6 py-3.5 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pendingAuthors as $applicant)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 text-sm">{{ $applicant->name }}</div>
                            <div class="text-gray-500">{{ $applicant->email }}</div>
                            @if($applicant->phone_number)
                                <div class="text-gray-400 text-[10px]">📞 {{ $applicant->phone_number }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            <div class="font-medium">{{ $applicant->university->name ?? 'No University' }}</div>
                            <div class="text-gray-500 text-[11px]">{{ $applicant->department ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $applicant->author_applied_at?->format('d M Y') ?? $applicant->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <!-- Detail Button (opens modal) -->
                            <button type="button"
                                    onclick="openDetailModal({{ $applicant->id }})"
                                    class="px-3 py-1.5 border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold uppercase tracking-wider text-[10px] transition-colors">
                                Detail
                            </button>

                            <!-- Quick Approve Button -->
                            <form action="{{ route('admin.authors.approve', $applicant) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" data-tour="authors-approve-btn" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white font-bold uppercase tracking-wider text-[10px] shadow-sm transition-colors">
                                    Approve
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- Detail Modal for this applicant --}}
                    <div id="modal-{{ $applicant->id }}"
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 hidden"
                         onclick="if(event.target===this) closeDetailModal({{ $applicant->id }})">
                        <div class="bg-white max-w-xl w-full mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-[#00081e]">
                                <h3 class="text-sm font-bold text-white font-heading uppercase tracking-wider">Application Detail</h3>
                                <button onclick="closeDetailModal({{ $applicant->id }})" class="text-gray-400 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-6 space-y-4 text-xs">
                                <!-- Applicant Info -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-gray-50 p-3 border border-gray-100">
                                        <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Name</div>
                                        <div class="font-semibold text-gray-900">{{ $applicant->name }}</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 border border-gray-100">
                                        <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Email</div>
                                        <div class="text-gray-700 break-all">{{ $applicant->email }}</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 border border-gray-100">
                                        <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">University</div>
                                        <div class="font-semibold text-gray-900">{{ $applicant->university->name ?? '-' }}</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 border border-gray-100">
                                        <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Faculty / Dept.</div>
                                        <div class="text-gray-700">{{ $applicant->department ?? '-' }}</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 border border-gray-100">
                                        <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Phone Number</div>
                                        <div class="text-gray-700">{{ $applicant->phone_number ?? '-' }}</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 border border-gray-100">
                                        <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Author Slug</div>
                                        <div class="text-gray-700 font-mono">{{ $applicant->page_name ?? '-' }}</div>
                                    </div>
                                </div>

                                <!-- Bio / Statement -->
                                <div class="bg-gray-50 p-3 border border-gray-100">
                                    <div class="text-[10px] text-gray-400 uppercase tracking-wider mb-2">Bio / Why Contribute?</div>
                                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $applicant->author_bio ?? 'No statement provided.' }}</p>
                                </div>

                                <div class="text-[10px] text-gray-400 text-right">
                                    Applied: {{ $applicant->author_applied_at?->format('d M Y, H:i') ?? $applicant->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>

                            <!-- Modal Actions -->
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between gap-3">
                                <!-- Reject with reason -->
                                <form action="{{ route('admin.authors.reject', $applicant) }}" method="POST" class="flex-1"
                                      data-confirm-title="Reject author application?"
                                      data-confirm-description="Are you sure you want to reject this applicant?"
                                      data-confirm-btn="Reject"
                                      data-confirm-variant="warning">
                                    @csrf
                                    <div class="flex gap-2">
                                        <input type="text"
                                               name="reason"
                                               placeholder="Alasan penolakan (opsional)"
                                               class="flex-1 border border-gray-300 px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:border-[#8b1528]">
                                        <button type="submit"
                                                class="px-4 py-1.5 border border-red-300 text-red-700 hover:bg-red-50 font-bold uppercase tracking-wider text-[10px] transition-colors whitespace-nowrap">
                                            Reject
                                        </button>
                                    </div>
                                </form>

                                <!-- Approve -->
                                <form action="{{ route('admin.authors.approve', $applicant) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="px-5 py-1.5 bg-green-600 hover:bg-green-700 text-white font-bold uppercase tracking-wider text-[10px] shadow-sm transition-colors">
                                        ✓ Approve
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">No pending applications at this time.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: Active Authors & Staff -->
    <div class="bg-white border border-gray-200 shadow-sm overflow-hidden" data-tour="authors-active-section">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-[#fcfcfd]">
            <h2 class="text-base font-bold font-heading text-[#00081e]">Active Authors &amp; Contributors ({{ $activeAuthors->total() }})</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-sans">
                <thead class="bg-[#f8f9fa] text-gray-500 uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 font-bold">Author</th>
                        <th class="px-6 py-3.5 font-bold">University &amp; Department</th>
                        <th class="px-6 py-3.5 font-bold">Role</th>
                        <th class="px-6 py-3.5 font-bold">Status</th>
                        <th class="px-6 py-3.5 font-bold">Articles</th>
                        <th class="px-6 py-3.5 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($activeAuthors as $user)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 text-sm">{{ $user->name }}</div>
                            <div class="text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            <div class="font-medium">{{ $user->university->name ?? 'None' }}</div>
                            <div class="text-gray-500 text-[11px]">{{ $user->department ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 text-gray-800 text-[11px] font-semibold">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->approvalToken)
                                <span class="px-2.5 py-0.5 bg-amber-50 text-amber-800 font-semibold border border-amber-200 text-[11px]" title="Awaiting password creation & verification">Awaiting Setup</span>
                            @elseif($user->author_status === 'approved')
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-800 font-semibold border border-green-200 text-[11px]">Active</span>
                            @elseif($user->author_status === 'suspended')
                                <span class="px-2.5 py-0.5 bg-red-100 text-red-800 font-semibold border border-red-200 text-[11px]">Suspended</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 border border-gray-200 text-[11px]">{{ ucfirst($user->author_status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-medium">
                            {{ $user->articles_count ?? $user->articles()->count() }} stories
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            @if($user->approvalToken)
                                <form action="{{ route('admin.authors.cancel-approval', $user) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      data-confirm-title="Cancel author approval?"
                                      data-confirm-description="Are you sure you want to cancel the author approval for &quot;{{ addslashes($user->name) }}&quot;? The user has not completed setup yet and will be reverted to Reader."
                                      data-confirm-btn="Cancel Approval"
                                      data-confirm-variant="warning">
                                    @csrf
                                    <button type="submit" class="text-orange-600 hover:text-orange-800 font-semibold">
                                        Cancel Approval
                                    </button>
                                </form>
                            @endif

                            @if($user->author_status === 'suspended')
                                <form action="{{ route('admin.authors.approve', $user) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 font-semibold">
                                        Unsuspend
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.authors.suspend', $user) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      data-confirm-title="Suspend author account?"
                                      data-confirm-description="Are you sure you want to suspend &quot;{{ addslashes($user->name) }}&quot;? The author will not be able to publish new articles."
                                      data-confirm-btn="Suspend"
                                      data-confirm-variant="warning">
                                    @csrf
                                    <button type="submit" data-tour="authors-suspend-btn" class="text-amber-600 hover:text-amber-800 font-semibold">
                                        Suspend
                                    </button>
                                </form>
                            @endif

                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.authors.destroy', $user) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      data-confirm-title="Delete author account?"
                                      data-confirm-description="Are you sure you want to permanently delete &quot;{{ addslashes($user->name) }}&quot;? All articles belonging to this author will be transferred to Admin."
                                      data-confirm-btn="Delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold ml-1">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400 italic">No active authors.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activeAuthors->hasPages())
        <div class="p-4 border-t border-gray-200 bg-[#f8f9fa]">
            {{ $activeAuthors->links() }}
        </div>
        @endif
    </div>

</div>

<script>
function openDetailModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeDetailModal(id) {
    document.getElementById('modal-' + id).classList.add('hidden');
    document.body.style.overflow = '';
}
// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="modal-"]').forEach(function(m) {
            m.classList.add('hidden');
        });
        document.body.style.overflow = '';
    }
});
</script>
@endsection
