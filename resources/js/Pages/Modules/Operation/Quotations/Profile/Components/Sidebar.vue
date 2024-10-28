<template>
    <table class="table table-bordered">
    <tbody>
        <tr>
            <td class="border-top-none" style="border-right: none; border-top: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">Quotation Information</span></td>
        </tr>
        <tr>
            <td style="border-right: none; border-left: none;">
                <div class="row ms-n2 mb-0">
                    <div class="col-md-12">
                        <div class="d-flex mt-0">
                            <div class="flex-shrink-0 avatar-xs align-self-center me-3">
                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-flask-fill"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-1 fs-12 text-muted">Laboratory :</p> 
                                <h6 class="text-truncate mb-0 fs-12">
                                    {{selected.type.name}}
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
                                <div class="avatar-title bg-light rounded-circle fs-16 text-primary"><i class="ri-qr-code-fill"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-1 fs-12 text-muted">Discount :</p> 
                                <h6 class="text-truncate mb-0 fs-12">
                                    <span >{{selected.discounted.name}} ({{selected.discounted.value }}%)</span>
                                </h6>
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
            <td style="border-right: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">Payment Details</span></td>
        </tr>
        <tr style="border-bottom: none; border-left: none;">
            <td style="border-right: none; border-bottom: none; border-left: none;">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <tbody class="fs-12">
                                <!-- <tr>
                                    <td>Wallet :</td>
                                    <td class="text-end" id="cart-subtotal">{{selected.wallet.available}}</td>
                                </tr> -->
                                <tr>
                                    <td>Sub Total :</td>
                                    <td class="text-end" id="cart-subtotal">{{selected.subtotal}}</td>
                                </tr>
                                <tr>
                                    <td><span v-if="selected.discounted.value != 0">({{selected.discounted.value}}%)</span>  Discount : </td>
                                    <td class="text-end" id="cart-discount">{{selected.discount}}</td>
                                </tr>
                                <tr class="table-active">
                                    <th>Total :</th>
                                    <td class="text-end"><span class="fw-semibold" id="cart-total"> {{selected.total}} </span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
        
    </tbody>
</table>
</template>
<script>
import simplebar from "simplebar-vue";
export default {
    components: { simplebar },
    props: ['selected']
}
</script>