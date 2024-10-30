<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" title="Create Order of Payment" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12 mt-2">
                    <InputLabel for="customer" value="Customer" :message="form.errors.customer_id"/>
                    <Multiselect 
                    :options="customers" 
                    @search-change="fetchCustomer" 
                    v-model="form.customer_id" 
                    label="name"
                    @input="handleInput('customer_id')"
                    :searchable="true" 
                    placeholder="Select Customer"/>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="collection" value="Collection" :message="form.errors.collection_id"/>
                    <Multiselect 
                    :options="collections" 
                    v-model="form.collection_id"
                    label="name"
                    placeholder="Select Collection type"/>
                </BCol>
                 <BCol lg="6" class="mt-1">
                    <InputLabel for="payment" value="Payment" :message="form.errors.payment_id"/>
                    <Multiselect 
                    :options="payments" 
                    v-model="form.payment_id"
                     @input="handleInput('payment_id')"
                    label="name"
                    placeholder="Select Payment type"/>
                </BCol>
                <BCol lg="12" class="mt-1">
                    <InputLabel for="customer" value="Subsidiary Customer/'s (Optional)" :message="form.errors.customer"/>
                    <Multiselect 
                    :options="subcustomers" 
                    @search-change="fetchSubcustomer" 
                    v-model="subcustomer_id" 
                    mode="tags"
                    label="name"
                    :searchable="true" 
                    placeholder="Select Subsidiary Customer"/>
                </BCol>
                <BCol lg="12">
                    <hr class="text-muted mt-0 mb-3"/>
                </BCol>
                <BCol lg="12">
                    <div class="table-responsive mt-n3">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="fs-11">
                                    <th width="5%" class="text-center">
                                        <!-- <input class="form-check-input fs-16" v-model="mark" type="checkbox" value="option" /> -->
                                    </th>
                                    <th width="55%">TSR Number</th>
                                    <th class="text-center" width="20%">Status</th>
                                    <th class="text-center" width="20%">Subtotal</th>
                                </tr>
                            </thead>
                        </table>
                        <simplebar style="max-height: calc(100vh - 550px); overflow: auto;">
                            <table class="table table-nowrap align-middle mb-0">
                                <tbody>
                                    <tr v-for="(list,index) in tsrs" v-bind:key="index">
                                        <td width="5%" class="text-center">
                                            <input type="checkbox" v-model="list.selected" class="form-check-input" />
                                        </td>
                                        <td width="55%">
                                            <h5 class="fs-13 mb-0 text-dark">{{list.code}}</h5>
                                            <p class="fs-12 text-muted mb-0">{{list.customer.name}}</p>
                                        </td>
                                        <td width="20%" class="text-center">
                                           <span :class="'badge '+list.payment.status.color+' '+list.payment.status.others">{{list.payment.status.name}}</span>
                                        </td>
                                         <td width="20%" class="text-center">{{list.payment.total}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </simplebar>
                        <table v-if="tsrs.length > 0" class="table table-nowrap align-middle mb-0">
                            <tfoot class="table-light">
                                <tr class="fs-11">
                                    <th width="5%"></th>
                                    <th width="55%"></th>
                                    <th class="text-center" width="20%">Total</th>
                                    <th class="text-center" width="20%">{{total}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </Bcol>
            </BRow>
        </form>  
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import simplebar from "simplebar-vue";
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, simplebar},
    props: ['collections','payments'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                customer_id: null,
                collection_id: 54,
                payment_id: null,
                selected: [],
                total: null,
                option: 'op'
            }),
            customer_id: null,
            subcustomer_id: null,
            ids: [],
            combinedIds: [],
            customers: [],
            subcustomers: [],
            tsrs: [],
            showModal: false,
            editable: false
        }
    },
    watch: {
        "form.customer_id"(newVal){
                this.tsrs = [];
                this.combinedIds = [];
                this.form.selected = [];
                this.subcustomer_id = null;
                (newVal) ? this.ids.push(newVal) : this.ids = [];
                this.combinedIds.push(newVal);
                (newVal) ? this.fetchTsrs() : '';
        },
        "subcustomer_id"(newVal){
            this.combinedIds = this.ids.concat(this.subcustomer_id);
            (newVal) ? this.fetchTsrs() : '';
        },
        'tsrs': {
            deep: true,
            handler() {
                this.form.selected = this.tsrs
                .filter(item => item.selected)
                .map(selectedItem => selectedItem);
            }
        }
    },
    computed: {
        total(){
            let s = this.form.selected.reduce((acc, item) => acc + parseInt(item.payment.total.replace('₱', '').replace(/,/g, '')), 0);
            this.form.total = s;
            let val = (s/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }
    },
    methods: { 
        show(){
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
        fetchCustomer(code){
            axios.get('/orderofpayments',{
                params: {
                    option: 'customers',
                    keyword: code
                }
            })
            .then(response => {
                this.customers = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchSubcustomer(code){
            axios.get('/orderofpayments',{
                params: {
                    option: 'customers',
                    id: this.form.customer_id,
                    keyword: code
                }
            })
            .then(response => {
                this.subcustomers = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchTsrs(code){
            axios.get('/orderofpayments',{
                params: {
                    option: 'tsrs',
                    customer_id: this.combinedIds
                }
            })
            .then(response => {
                this.tsrs = response.data.data;
            })
            .catch(err => console.log(err));
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.customer_id = null;
            this.form.collection_id = null;
            this.form.payment_id = null;
            this.form.selected = [];
            this.form.total = null;
            this.showModal = false;
        }
    }
}
</script>
<style>
span.multiselect-tag:nth-child(1) {
  padding: 0px 7px;
  font-size: 9px;
}
span.multiselect-tag:nth-child(2) {
  padding: 0px 7px;
  font-size: 9px;
}
span.multiselect-tag:nth-child(3) {
  padding: 0px 7px;
  font-size: 9px;
}
span.multiselect-tag:nth-child(4) {
  padding: 0px 7px;
  font-size: 9px;
}
</style>