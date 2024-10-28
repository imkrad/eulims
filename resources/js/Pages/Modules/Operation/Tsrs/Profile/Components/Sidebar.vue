<template>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">TSR Information</span></td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;">
                    <div class="row ms-n2 mb-0">
                        <div class="col-md-12">
                            <div class="d-flex mt-0">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-qr-code-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Code :</p> 
                                    <h6 class="text-truncate mb-0 fs-12">
                                        <span class="fw-semibold" v-if="selected.code">{{selected.code}}</span>
                                        <span class="text-muted" v-else>Not yet Available</span>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex mt-3">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-service-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Status :</p> 
                                    <span :class="'badge '+selected.status.color">{{selected.status.name}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex mt-3">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-event-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Due At :</p>
                                    <h6 class="text-truncate mb-0 fs-12" v-if="selected.due_at">{{selected.due_at}}</h6>
                                    <span class="text-warning mb-0 fs-12" v-else>Not yet set</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">Received Information</span></td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;">
                    <div class="row ms-n2 mb-0">
                        <div class="col-md-12">
                            <div class="d-flex mt-0">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Requested Date :</p> 
                                    <h6 class="text-truncate mb-0 fs-12">{{selected.created_at}}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex mt-3">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-account-circle-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Received By :</p>
                                    <h6 class="text-truncate mb-0"> <span class="fs-12">{{selected.received}}</span></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">Accounting Information</span></td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;">
                    <div class="row ms-n2 mb-0">
                        <div class="col-md-12">
                            <div class="d-flex">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-qr-code-fill"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">OR Number :</p>
                                    <h6 class="text-truncate mb-0 fs-12" v-if="selected.payment.or_number">{{selected.payment.or_number}}</h6>
                                    <span class="text-warning mb-0 fs-12" v-else>Not Available</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex mt-3">
                                <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                    <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-calendar-line"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-1 fs-12 text-muted">Paid At :</p>
                                    <h6 class="text-truncate mb-0 fs-12" v-if="selected.payment.paid_at">{{selected.payment.paid_at}}</h6>
                                    <span class="text-warning mb-0 fs-12" v-else>Not Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr v-if="selected.service">
                <td style="border-right: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">Add Ons</span></td>
            </tr>
            <tr v-if="selected.service">
                <td style="border-right: none; border-left: none;">
                    <div v-if="selected.service" class="alert alert-warning fs-12 mb-0" role="alert">
                        <span class="fw-semibold">{{selected.service.service.name}}</span> <br /><span class="fs-11 text-muted">({{selected.service.service.description}})</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="border-right: none; border-left: none;">
                    <span class="fw-semibold fs-12 ms-2">Payment Details</span>
                    <i @click="openPayment()" class="ri-information-fill fs-20 mb-n2 mt-n1 text-primary float-end" style="cursor: pointer;"></i>
                </td>
            </tr>
            <tr style="border-bottom: none; border-left: none;">
                <td style="border-right: none; border-bottom: none; border-left: none;">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody class="fs-12">
                                    <tr>
                                        <td width="50%">Sub Total :</td>
                                        <td width="50%" class="text-end" id="cart-subtotal">{{selected.payment.subtotal}}</td>
                                    </tr>
                                    <tr>
                                        <td><span v-if="selected.payment.discounted.value != 0">({{selected.payment.discounted.value}}%)</span>  Discount : </td>
                                        <td class="text-end" id="cart-discount">{{selected.payment.discount}}</td>
                                    </tr>
                                    <tr v-if="selected.payment.has_deduction">
                                        <td>Wallet Deduction:</td>
                                        <td class="text-end" id="cart-subtotal">{{selected.payment.deduction.amount}}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <th>Total :</th>
                                        <td class="text-end"><span class="fw-semibold" id="cart-total"> {{selected.payment.total}} </span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
<Payment ref="payment"/>
</template>
<script>
import simplebar from "simplebar-vue";
import Payment from '../Modals/Payment.vue';
export default {
    components: { simplebar, Payment },
    props: ['selected'],
    methods: {
        openPayment(){
            this.$refs.payment.show(this.selected.payment);
        }
    }
}
</script>