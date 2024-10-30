<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 800px;" header-class="p-3 bg-light" title="View Order of Payment" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="row mb-3">
            <div class="col-md">
                <div class="row align-items-center g-3">
                    <div class="col-md">
                        <div >
                            <h5 class="fs-15 fw-semibold text-primary mb-2">{{selected.payorable.name}}</h5>
                            <div class="hstack gap-3 flex-wrap">
                                <div class="text-muted"><i class="ri-map-pin-fill me-1"></i>{{selected.payorable.address}}</div>
                                <div class="vr"></div>
                                <div class="text-muted"><i class="ri-calendar-2-fill me-1"></i>{{selected.date}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="text-muted"/>
        <div class="card-body text-center">
            <div class="row g-3">
                <div class="col-lg-3 col-6" v-if="selected.or">
                    <p class="text-muted mb-2 fs-11 text-uppercase fw-semibold">OR Number</p>
                    <h5 class="fs-12 mb-0"><span id="invoice-no">{{selected.or.number}}</span></h5>
                </div>
                <div class="col-lg-3 col-6" v-else>
                    <p class="text-muted mb-2 fs-11 text-uppercase fw-semibold">Code</p>
                    <h5 class="fs-12 mb-0"><span id="invoice-no">{{selected.code}}</span></h5>
                </div>
                <div class="col-lg-3 col-6">
                    <p class="text-muted mb-2 fs-11 text-uppercase fw-semibold">Payment</p>
                    <h5 class="fs-12 mb-0"><span id="invoice-no">{{selected.payment.name}}</span></h5>
                </div>
                <div class="col-lg-3 col-6">
                    <p class="text-muted mb-2 fs-11 text-uppercase fw-semibold">Collection</p>
                     <h5 class="fs-12 mb-0"><span id="invoice-no">{{selected.collection}}</span></h5>
                </div>
                <div class="col-lg-3 col-6">
                    <p class="text-muted mb-2 fs-11 text-uppercase fw-semibold">Status</p>
                    <h5 class="fs-12 mb-0">{{selected.status.name}}</h5>
                </div>
            </div>
        </div>
        <hr class="text-muted"/>
        <table class="table table-nowrap table-bordered align-middle mb-0">
            <thead>
                <tr class="table-light fs-11">
                    <th style="width: 50%;" class="text-center">TSR Number</th>
                    <th style="width: 50%;" class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fs-12" v-for="(list,index) in selected.items" v-bind:key="index">
                    <td class="text-center">{{(list.itemable.code) ? list.itemable.code : list.itemable.name}}</td>
                    <td class="text-center">{{list.amount}}</td>
                </tr>
            </tbody>
        </table>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button v-if="selected.status.name == 'Pending'" @click="openOr" variant="primary" :disabled="form.processing" block>Create Receipt</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    props: ['deposits','orseries'],
    data(){
        return {
            currentUrl: window.location.origin,
            selected: {
                payorable: {},
                collection: {},
                payment: {},
                createdby: {
                    profile: {}
                },
                status: {},
                customer: {
                    customer_name: {},
                    address: {
                        barangay: {},
                        municipality: {},
                        province: {},
                        region: {}
                    }
                }
            },
            form: {},
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        },
        openOr(){
            this.$refs.or.show(this.selected.customer,this.selected);
        },
        update(){
            this.$emit('update',true);
            this.showModal = false;
        }
    }
}
</script>