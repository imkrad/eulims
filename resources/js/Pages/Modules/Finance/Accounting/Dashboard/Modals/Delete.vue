<template>
    <b-modal v-model="showModal" hide-footer hide-header title="Delete Order of Payment" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="text-center" v-if="selected">
            <div class="mt-4">
                <h5 class="mb-3">Delete Order of Payment</h5>
                <p class="text-muted mb-2">Are you sure you want to delete order of payment for <br/> <span class="fw-bold text-primary">{{selected.payorable.name}}</span>?</p>
                <p class="text-muted fs-12 mb-4">It will revert all changes on the tsr payment.</p>
                <div class="hstack gap-2 justify-content-center mb-3">
                    <button @click="hide()" class="btn btn-light btn-md" type="button">
                        <div class="btn-content"> Close</div>
                    </button>
                    <a @click="submit()" class="btn btn-danger" href="javascript:void(0);" target="_self">Confirm</a>
                </div>
            </div>
        </div>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
               id: null,
               items: null,
               option: 'delete'
            }),
            option: null,
            selected: null,
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.form.id = data.id;
            this.form.items = data.items;
            this.selected = data;
            this.showModal = true;
        },
        submit(){
            this.form.post('/orderofpayments',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('update',true);
                    this.hide();
                },
            });
        },
        hide(){
            this.option = null;
            this.showModal = false;
        }
    }
}
</script>