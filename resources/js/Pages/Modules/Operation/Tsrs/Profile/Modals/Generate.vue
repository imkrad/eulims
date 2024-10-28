<template>
    <b-modal v-if="selected" style="--vz-modal-width: 600px;" v-model="showModal" header-class="p-3 bg-light" title="Generate TSR" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12" class="mt-2">
                    <InputLabel for="days" value="Customer"/>
                    <TextInput v-model="form.customer" type="text" class="form-control" :light="true" :readonly="true"/>
                </BCol>
                 <BCol lg="12" class="mt-0">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel for="conforme" value="Conforme" :message="form.errors.conforme_id"/>
                            <Multiselect 
                            :options="conformes" 
                            v-model="form.conforme_id" 
                            label="name"
                            @input="handleInput('conforme')"
                            :searchable="true" 
                            placeholder="Select Conforme"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="openAdd()" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-add-circle-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="6" class="mt-1">
                    <InputLabel for="days" value="Laboratory"/>
                    <TextInput v-model="form.laboratory" type="text" class="form-control" :light="true" :readonly="true"/>
                </BCol>
                 <BCol lg="6" class="mt-1">
                    <InputLabel for="due" value="Report Due" :message="form.errors.due_at"/>
                    <TextInput v-model="form.due_at" type="date" class="form-control" autofocus placeholder="Please enter email" autocomplete="email" required @input="handleInput('due_at')" :light="true"/>
                </BCol>
                <BCol lg="12" class="mt-0"><hr class="text-muted"/></BCol>
                <BCol lg="12" class="mt-n2 mb-n3">
                    <InputLabel for="name" value="Sample Name" :message="form.errors.name"/>
                    <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="Please enter name" autocomplete="name" :light="true"/>
                </BCol>
                <BCol lg="6" class="mb-1">
                    <InputLabel for="name" value="Description provided by customer"/>
                    <Textarea id="name" v-model="form.customer_description" class="form-control" rows="2" :class="{ 'is-invalid': form.errors.customer_description }" :light="true"/>
                </BCol>
                <BCol lg="6" class="mb-1">
                    <InputLabel for="name" value="Description based on the sample submitted"/>
                    <Textarea id="name" v-model="form.description" class="form-control" rows="2" :class="{ 'is-invalid': form.errors.description }" :light="true"/>
                </BCol>
                <BCol lg="12" class="mb-1">
                    <input class="form-check-input fs-16" v-model="form.has_control" type="checkbox" value="option" /> <span class="text-muted">Has control sample?</span>
                </BCol>
            </BRow>
        </form> 
<!-- 
        <table class="table table-nowrap align-middle mb-2">
            <thead class="table-light thead-fixed">
                <tr class="fs-11">
                    <th width="70%">Testname</th>
                    <th class="text-center" width="30%">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in selected.items" :key="item.id">
                    <td>
                        <h5 class="fs-12 mb-0">{{item.testservice.testname.name}}</h5>
                    </td>
                    <td class="text-center">{{formatMoney(item.total)}}</td>
                </tr>
            </tbody>
        </table> -->
    
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" block>Submit</b-button>
        </template>
    </b-modal>
    <Add @selected="set" ref="conforme"/>
    <Testservice :laboratories="laboratories" @success="addItems" ref="testservice"/>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Testservice from './Testservice.vue';
import Add from '../../Modals/Add.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
export default {
    components: { InputLabel, Multiselect, TextInput, Testservice, Add, Textarea },
    props: ['laboratories'],
    data(){
        return {
            currentUrl: window.location.origin,
            selected: {},
            form: useForm({
                name: null,
                customer_description: null,
                description: null,
                customer: null,
                customer_name: null,
                conforme_id: null,
                customer_id: null,
                customer: null,
                laboratory: null,
                laboratory_type: null,
                status_id: 3,
                tsr_id: null,
                due_at: null,
                has_control: false,
                lists: [],
                payment: {
                    discount_id: null,
                    collection_id: null,
                    payment_id: null,
                    status_id: null,
                    or_number: null,
                    is_child: 1,
                },
                option: 'child'
            }),
            selected: null,
            showModal: false
        }
    },
    computed: {
        conformes() {
            return this.selected.conformes.map(item => {
                return {
                    value: item.value,
                    name: item.name
                };
            });
        }
    },
    methods: { 
        show(data){
            console.log(data);
            this.form.customer = data.customer;
            this.form.customer_id = data.customer_id;
            this.form.laboratory = data.typeName;
            this.form.laboratory_type = data.typeId;
            this.form.lists = data.items;
            this.form.payment.collection_id = data.collection_id;
            this.form.payment.payment_id = data.payment_id;
            this.form.payment.discount_id = data.discount_id;
            this.form.payment.status_id = data.status_id;
            this.form.payment.or_number = data.or_number;
            this.form.tsr_id = data.tsr_id;
            this.selected = data;
            this.showModal = true;
        }, 
        submit(){
            this.form.put('/requests/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('success',true);
                    this.hide();
                },
            });
        },
        addItems(data){
            this.form.lists.push(...data);
        },
        openTestservice(){
            this.$refs.testservice.show();
        },
        openAdd(){
            this.$refs.conforme.show(this.selected.customer);
        },
        set(data){
            this.customer.conformes.push(data);
            this.form.conforme = data;
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        hide(){
            this.form.reset();
            this.showModal = false;
        }
    }
}
</script>