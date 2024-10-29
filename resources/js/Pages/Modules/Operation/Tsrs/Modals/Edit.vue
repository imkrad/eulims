<template>
    <b-modal v-if="selected" v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="Edit TSR" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <div class="ms-2 mt-n2">
            <h5 class="modal-title ms-n2 fs-16">{{ selected.code }}</h5>
            <div class="hstack gap-3 flex-wrap mt-0 ms-n2 mb-n2">
                <div class="text-primary">Customer :
                   {{ selected.customer.name }}
                </div>
            </div>
        </div>
        <hr class="text-muted"/>
        <div class="row customform g-2 mt-n2">
            <BCol lg="12" class="mt-2">
                <InputLabel for="due" value="Report Due" :message="form.errors.due_at"/>
                <TextInput v-model="form.due_at" type="date" class="form-control" placeholder="Please enter email" @input="handleInput('due_at')" :light="true"/>
            </BCol>
            <BCol lg="6" class="mt-0">
                <InputLabel for="region" value="Discount" :message="form.errors.discount_id"/>
                <Multiselect 
                :options="dropdowns.discounts" 
                v-model="form.discount_id"
                @input="handleInput('discount_id')"
                :searchable="true" label="name"
                placeholder="Select Discount"/>
            </BCol>
            <BCol lg="6" class="mt-0">
                <InputLabel for="region" value="Purpose" :message="form.errors.purpose_id"/>
                <Multiselect 
                :options="dropdowns.purposes" 
                v-model="form.purpose_id"
                @input="handleInput('purpose_id')"
                :searchable="true" label="name"
                placeholder="Select Purpose"/>
            </BCol>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <!-- <b-button variant="primary" block>Confirm</b-button> -->
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    props: ['dropdowns'],
    components: { InputLabel, TextInput, Multiselect },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            form: useForm({
                id: null,
                purpose_id: null,
                discount_id: null,
                due_at: null
            }),
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.form.id = data.id;
            this.form.due_at = this.convertToISO(data.due_at);
            this.form.purpose_id = (data.purpose) ? data.purpose.id : null;
            this.form.discount_id = data.payment.discount_id;
            this.showModal = true;
        },
        convertToISO(dateString) {
            const date = new Date(dateString);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>