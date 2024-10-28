<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="Add Service" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                 <BCol lg="12" class="mt-1">
                    <InputLabel for="region" value="Service"/>
                    <Multiselect 
                    label="label"
                    :options="services" 
                    object
                    v-model="form.service"
                    placeholder="Select Service"/>
                </BCol>
                <div class="col-lg-12" v-if="form.service"><hr class="text-muted"/></div>
                <div class="col-lg-6 mt-0" v-if="form.service">
                    <div class="form-floating">
                        <input type="text" class="form-control" :value="form.service.name" readonly=""><label>Name</label>
                    </div>
                </div>
                <div class="col-lg-6 mt-0" v-if="form.service">
                    <div class="form-floating">
                        <input type="text" class="form-control" :value="form.service.fee" readonly=""><label>Fee</label>
                    </div>
                </div>
                <div class="col-lg-12" v-if="form.service">
                    <div class="form-floating">
                        <input type="text" class="form-control" :value="form.service.description" readonly=""><label>Description</label>
                    </div>
                </div>
                <div class="col-lg-12" v-if="form.service"><hr class="text-muted"/></div>
                <BCol lg="12" class="mt-n2 mb-n2" v-if="form.service">
                    <InputLabel for="name" value="Please enter the number of times the additional fee will be multiplied?"/>
                    <TextInput id="name" type="text" v-model="form.quantity" class="form-control" autofocus placeholder="Please enter quantity" autocomplete="name" required/>
                </BCol>
                <BCol lg="12" v-if="form.service" class="mt-0">
                    <hr class="text-muted"/>
                </BCol>
            </BRow>     
        </form>       
        <div class="row g-2 mt-n2" v-if="form.service">
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
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Multiselect from "@vueform/multiselect";
export default {
    components: { Multiselect, TextInput, InputLabel},
    props: ['services'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                service: null,
                total: null,
                quantity: 1,
                option: 'service'
            }),
            showModal: false
        }
    },
    computed: {
        total() {
            if(this.form.service){
                const total = this.form.service.fee.replace(/₱|,/g, '') * this.form.quantity;
                this.form.total = total;
                return this.formatMoney(total);
            }else{
                return '0.00';
            }
        }
    },
    methods: { 
        show(id){
            this.form.id = id;
            this.showModal = true;
        },
        submit(){
            this.form.post('/analyses',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('total',response.props.flash.data);
                    this.hide();
                },
            });
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        hide(){
            this.form.name = null;
            this.form.contact_no = null;
            this.showModal = false;
        }
    }
}
</script>