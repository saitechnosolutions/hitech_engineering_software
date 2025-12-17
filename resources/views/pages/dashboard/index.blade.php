@extends('layouts.app')
@section('main-content')
<x-breadcrumb
    :items="[
        ['label' => 'Hi-tech Engineering', 'url' => '#'],
        ['label' => 'Pages', 'url' => '#'],
        ['label' => 'Dashboard']
    ]"
    title="Dashboard" />

<style>
.accordion-button {
    position: relative;
    width: 100%;
    text-align: left;
    font-weight: 500;
}

.accordion-button::after {
    content: '\002B'; /* Plus sign */
    font-size: 1.2rem;
    position: absolute;
    right: 20px;
    transition: transform 0.2s, content 0.2s;
}

.accordion-button.collapsed::after {
    content: '\002B'; /* Plus sign */
}

.accordion-button:not(.collapsed)::after {
    content: '\2212'; /* Minus sign */
}
</style>

     @if($roleId == 1)
        @include('pages.dashboard.sections.admin_dashboard', ['orderDetails' => $orderDetails, 'processTeams' => $processTeams, 'completedQuotations' => $completedQuotations, 'recentTasks' => $recentTasks, 'todayPaymentColledtedCount' => $todayPaymentColledtedCount, 'activeOrdersCount' => $activeOrdersCount, 'collectionPendingAmount' => $collectionPendingAmount, 'revenueAmount' => $revenueAmount, 'inProductionCount' => $inProductionCount])
        @elseif($roleId == 3)
        @include('pages.dashboard.sections.accounts_dashboard', ['invoiceRequests' => $invoiceRequests, 'activeOrdersCount' => $activeOrdersCount, 'retailOrdersCount' => $retailOrdersCount, 'retailOrdersCountCompleted' => $retailOrdersCountCompleted])
        @else
        @include('pages.dashboard.sections.tl_dashboard', ['quotations' => $quotations, 'role' => $roleId, 'teamIds' => $teamIds, 'roleName' => $roleName, 'inProductionCount' => $inProductionCount])

    @endif

    @include('pages.dashboard.modals.allocate_employee_modal', ['employees' => $employees, 'teamId' => $teamId])
    @include('pages.dashboard.modals.allocate_dispatch_employee_modal', ['teamId' => $teamId])
    @include('pages.dashboard.modals.production_history_modal')
    @include('pages.dashboard.modals.production_history_show')
    @include('pages.dashboard.modals.accept_modal')
    @include('pages.dashboard.modals.dispatch_modal', ['quotations' => $quotations])
@endsection

@push('scripts')
    <script>
        // Capture photo click
let stream;

$(document).on("click", ".takePhoto", function() {
    let qid = $(this).data("quotationid");
    $('#quotation_id').val(qid);

    startCamera();
});

function startCamera() {
    navigator.mediaDevices.getUserMedia({ video: true })
    .then(function(s) {
        stream = s;
        document.getElementById("cameraModal").style.display = "flex";
        document.getElementById("cameraStream").srcObject = stream;
    })
    .catch(function(err) {
        alert("Camera permission denied or not available.");
        console.log(err);
    });
}

document.getElementById("captureBtn").addEventListener("click", function() {
    let video = document.getElementById("cameraStream");
    let canvas = document.getElementById("cameraCanvas");
    let context = canvas.getContext("2d");

    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert to Base64
    let imageData = canvas.toDataURL("image/png");

    document.getElementById("capturedImage").value = imageData;

    stopCamera();

    document.getElementById("captureForm").submit();
});

function closeCamera() {
    stopCamera();
}

function stopCamera() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    document.getElementById("cameraModal").style.display = "none";
}


    </script>

    <script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
@endpush
