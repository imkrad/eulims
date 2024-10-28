<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 650px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Quotation' : 'Edit Quotation'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow class="g-3">
                <BCol lg="12">
                    <InputLabel for="customer" value="Customer" :message="form.errors.customer"/>
                    <Multiselect 
                    :options="customers" 
                    @search-change="fetchCustomer" 
                    v-model="form.customer" 
                    object label="name"
                    :searchable="true" 
                    @input="handleInput('customer')"
                    placeholder="Select Customer"/>
                </BCol>
                <BCol :lg="(form.conforme) ? 6 : 12" class="mt-1" v-if="form.customer">
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <InputLabel for="conforme" value="Conforme" :message="form.errors.conforme"/>
                            <Multiselect 
                            :options="form.customer.conformes" 
                            v-model="form.conforme" 
                            label="name"
                            object
                            @input="handleInput('conforme')"
                            :searchable="true" 
                            placeholder="Select Conforme"/>
                        </div>
                        <div class="flex-shrink-0">
                            <b-button @click="openAdd()" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-add-circle-fill"></i></b-button>
                        </div>
                    </div>
                </BCol>
                <BCol lg="6" v-if="(form.conforme) ? true : false" class="mt-1">
                    <InputLabel for="due" value="Mobile No" :message="form.errors.conforme"/>
                    <TextInput v-model="form.conforme.contact_no" type="text" class="form-control" autofocus placeholder="Please enter name" required :class="{ 'is-invalid': form.errors.conforme }" @input="handleInput('conforme')" :light="true"/>
                </BCol>
                <BCol lg="12">
                    <hr class="text-muted mt-0 mb-3"/>
                </BCol>
                <BCol lg="6" class="mt-n2">
                    <InputLabel for="region" value="Laboratory" :message="form.errors.laboratory_type"/>
                    <Multiselect 
                    :options="dropdowns.laboratories" 
                    v-model="form.laboratory_type"
                    @input="handleInput('laboratory_type')"
                    :searchable="true" label="name"
                    placeholder="Select Laboratory"/>
                </BCol>
                 <BCol lg="6" class="mt-n2">
                    <InputLabel for="region" value="Discount" :message="form.errors.discount_id"/>
                    <Multiselect 
                    :options="dropdowns.discounts" 
                    v-model="form.discount_id"
                    @input="handleInput('discount_id')"
                    :searchable="true" label="name"
                    placeholder="Select Discount"/>
                </BCol>
            </BRow>
        </form>
           
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
    <Add @selected="set" ref="conforme"/>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Add from './Add.vue';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { Multiselect, InputLabel, TextInput, Add },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                customer: null,
                conforme: null,
                laboratory_type: null,
                discount_id: null,
                option: 'quotation'
            }),
            customers: [],
            conformes: [],
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(){
            this.showModal = true;
        },
        submit(){
            this.form.post('/quotations',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('success',true);
                    this.hide();
                },
            });
        },
        fetchCustomer(code){
            axios.get('/customers',{
                params: {
                    option: 'pick',
                    keyword: code
                }
            })
            .then(response => {
                this.customers = response.data;
            })
            .catch(err => console.log(err));
        },
        openAdd(){
            this.$refs.conforme.show(this.form.customer);
        },
        set(data){
            this.form.customer.conformes.push(data);
            this.form.conforme = data;
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.form.reset();
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>