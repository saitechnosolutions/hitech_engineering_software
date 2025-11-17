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
        @include('pages.dashboard.sections.admin_dashboard', ['orderDetails' => $orderDetails, 'processTeams' => $processTeams, 'completedQuotations' => $completedQuotations, 'recentTasks' => $recentTasks, 'months' => $months, 'sales' => $sales, 'todayPaymentColledtedCount' => $todayPaymentColledtedCount, 'activeOrdersCount' => $activeOrdersCount, 'collectionPendingAmount' => $collectionPendingAmount, 'revenueAmount' => $revenueAmount])
        @elseif($roleId == 3)
        @include('pages.dashboard.sections.accounts_dashboard', ['invoiceRequests' => $invoiceRequests])
        @else
        @include('pages.dashboard.sections.tl_dashboard', ['quotations' => $quotations, 'role' => $roleId, 'teamIds' => $teamIds, 'roleName' => $roleName])

    @endif

    @include('pages.dashboard.modals.allocate_employee_modal', ['employees' => $employees, 'teamId' => $teamId])
    @include('pages.dashboard.modals.production_history_modal')
    @include('pages.dashboard.modals.production_history_show')
@endsection

