<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 550px;" header-class="p-3 bg-light" :title="(editable) ? 'Update Campus' : 'Add Campus'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <BRow class="g-3">
                        <BCol lg="12" class="mt-1">
                            <InputLabel value="Campus" :message="form.errors.campus"/>
                            <TextInput v-model="form.campus" type="text" class="form-control" placeholder="Please enter campus name" @input="handleInput('campus')" :light="true" />
                        </BCol>
                        <BCol lg="6" class="mt-0">
                            <InputLabel value="Grading" :message="form.errors.grading_id"/>
                            <Multiselect :options="dropdowns.gradings" v-model="form.grading_id" label="name" :searchable="true" placeholder="Select Grading" />
                        </BCol>
                        <BCol lg="6" class="mt-0">
                            <InputLabel value="Term type" :message="form.errors.term_id"/>
                            <Multiselect :options="dropdowns.terms" v-model="form.term_id" label="name" :searchable="true" placeholder="Select Term" />
                        </BCol>
                        <BCol lg="12" class="mt-1">
                            <InputLabel value="Agency" :message="form.errors.agency_id"/>
                            <Multiselect :options="dropdowns.agencies" v-model="form.agency_id" label="name" :searchable="true" placeholder="Select Agency" />
                        </BCol>
                        <BCol lg="12"><hr class="text-muted mt-0 mb-1"/></BCol>
                        <BCol lg="12" class="mt-1">
                            <div class="d-flex">
                                <div style="width: 100%;">
                                    <InputLabel value="Address" :message="form.errors.address"/>
                                    <TextInput readonly v-model="form.address" type="text" class="form-control" placeholder="Please enter address" @input="handleInput('address')" :light="true" />
                                </div>
                                <div class="flex-shrink-0">
                                    <b-button @click="addLocation(index)" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-map-pin-fill"></i></b-button>
                                </div>
                            </div>
                        </BCol>
                    </BRow>  
                </BCol>
            </BRow>
        </form>
        <Location :regions="dropdowns.regions" @submit="handleSubmit" ref="location"/>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Location from './Location.vue';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: {InputLabel, TextInput, Multiselect, Location },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                campus: null,
                info: null,
                agency_id: null,
                term_id: null,
                grading_id: null,
                school_id: null,
                address: null,
                region: null,
                province: null,
                municipality: null,
                barangay: null,
                option: 'campus'
            }),
            index: null,
            showModal: false,
            editable: false,
            selected: null,
        }
    },
    methods: { 
        show(id){
            this.form.reset();
            this.form.school_id = id;
            this.editable = false;
            this.showModal = true;
        },
        edit(data){
            this.form.reset();
            this.form.id = data.id;
            this.form.campus = data.campus;
            this.form.school_id = data.school_id;
            this.form.agency_id = data.agency_id;
            this.form.term_id = data.term_id;
            this.form.grading_id = data.grading_id;
            this.$refs.location.edit(data);
            this.editable = true;
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/management/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    },
                });
            }else{
                this.form.post('/management',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.form.reset();
                        this.hide();
                    },
                });
            }
        },
        handleSubmit(data) {
            const address = data.address;
            const index = data.index;

            if (index !== undefined) {
                this.form.address = address;
                this.form.info = data.form.info;
                this.form.region = data.form.region;
                this.form.province = data.form.province;
                this.form.municipality = data.form.municipality;
                this.form.barangay = data.form.barangay;
            }
        },
        addLocation(index){
            this.$refs.location.openEdit();
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>