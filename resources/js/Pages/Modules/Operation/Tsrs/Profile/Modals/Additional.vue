<template>
    <b-modal v-if="selected" v-model="showModal" header-class="p-3 bg-light" title="Add additional fee" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        
        <div class="row g-2 mt-n2">
            <div class="col-md-9">
                <div class="form-floating">
                    <input type="text" v-model="selected.name" class="form-control" readonly>
                    <label>Description</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" v-model="selected.fee" class="form-control" readonly>
                    <label>Fee</label>
                </div>
            </div>
            <div class="col-md-12 mt-0">
                <hr class="text-muted"/>
            </div>
        </div>
        <form class="customform">
            <BRow class="g-3 mt-1">
                <BCol lg="12" class="mt-n2 mb-n2">
                    <InputLabel for="name" value="Please enter the number of times the additional fee will be multiplied?"/>
                    <TextInput id="name" type="text" v-model="form.quantity" class="form-control" autofocus placeholder="Please enter quantity" autocomplete="name" required/>
                </BCol>
                <BCol lg="12" class="mt-0">
                    <hr class="text-muted"/>
                </BCol>
            </BRow>
        </form>
        <div class="row g-2 mt-n2">
            <div class="col-md-12">
                <!-- <span class="float-end">1,000</span> -->
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tbody class="fs-12">
                            <tr class="table-active">
                                <th>Total :</th>
                                <td class="text-end"><span class="fw-semibold" id="cart-total">{{total}}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
export default {
    components: { InputLabel, TextInput, Textarea },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            form: useForm({
                id: null,
                tsr_id: null,
                quantity: null,
                service: null,
                total: null,
                option: 'fee'
            }),
            showModal: false
        }
    },
    computed: {
        total() {
            const total = this.selected.fee.replace(/₱|,/g, '') * this.form.quantity;
            this.form.total = total;
            return this.formatMoney(total);
        }
    },
    methods: { 
        show(data,id,tsr){
            this.form.tsr_id = tsr;
            this.form.id = id;
            
            if(data.length == 1){
                this.selected = data[0];
                this.form.service = data[0];
            }
            this.showModal = true;
        },
        submit(){
            this.form.post('/analyses',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('success',true);
                    this.hide();
                },
            });
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>