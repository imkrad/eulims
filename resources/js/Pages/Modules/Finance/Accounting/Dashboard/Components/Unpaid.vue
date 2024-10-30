<template>
    <b-row class="g-2 mb-2 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text">No order of payment issued</span>
                <input type="text" placeholder="Search Customer" class="form-control" style="width: 55%;">
                <!-- <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="bx bx-refresh search-icon"></i>
                </span>
                <b-button v-if="$page.props.user.data.assigned_role == 'Accountant'" type="button" variant="primary" @click="openCreate">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                </b-button> -->
            </div>
        </b-col>
    </b-row>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th style="width: 80%;">Customer</th>
                    <th style="width: 20%;" class="text-center">Total</th>
                </tr>
            </thead>
            <tbody v-if="tsrs.data.length > 0">
                <tr v-for="(list,index) in tsrs.data" v-bind:key="index">
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{list.code}}</h5>
                        <p class="fs-12 text-muted mb-0">{{list.customer.name}}</p>
                    </td>
                    <td class="text-end">{{list.payment.total}}</td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        There are no records to display
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: ['tsrs']
}
</script>