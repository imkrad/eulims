<template>
    <b-modal v-if="selected" v-model="showModal" style="--vz-modal-width: 650px;" hide-footer title="Payment Details" class="v-modal-custom"  header-class="p-3 bg-light" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="card-body">
            <div class="row fs-12">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">OR Number:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{selected.or_number}}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">Collection:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{selected.collection.name}}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">Payment Method:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{selected.type.name}}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">Status:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{selected.status.name}}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">Discount:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{selected.discounted.name}}</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">Paid At:</p>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="mb-0 fs-12">{{selected.paid_at}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="text-muted mt-1"/>
        <div class="table-responsive fs-12">
            <table class="table table-bordered mt-0 mb-3">
                <tbody>
                    <tr>
                        <td>Sub Total :</td>
                        <td class="text-end" id="cart-subtotal">{{selected.subtotal}}</td>
                    </tr>
                    <tr>
                        <td>Discount : </td>
                        <td class="text-end" id="cart-discount">{{selected.discount}}</td>
                    </tr>
                    <tr class="table-active">
                        <th>Total :</th>
                        <td class="text-end"><span class="fw-semibold" id="cart-total"> {{selected.total}} </span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            selected: {
                status:{}, collection:{}, type:{}, discounted:{}
            },
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
        }
    }
}
</script>