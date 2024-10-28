<template>
    <PageHeader title="Create School" pageTitle="List" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <form class="customform">
                <BRow class="g-3">
                    <BCol lg="8" class="mt-1">
                        <InputLabel value="Name" :message="form.errors.name"/>
                        <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true" />
                    </BCol>
                    <BCol lg="2" class="mt-1">
                        <InputLabel value="Class" :message="form.errors.shortcut"/>
                        <Multiselect :options="dropdowns.classes" v-model="form.class_id" label="name" :searchable="true" placeholder="Select Class" />
                    </BCol>
                    <BCol lg="1" class="mt-1">
                        <InputLabel value="Shortcut" :message="form.errors.shortcut"/>
                        <TextInput v-model="form.shortcut" type="text" class="form-control" placeholder="Please enter shortcut" @input="handleInput('shortcut')" :light="true" />
                    </BCol>
                    <BCol lg="1" class="mt-1">
                        <InputLabel value="Combiner" :message="form.errors.combiner"/>
                        <TextInput v-model="form.combiner" type="text" class="form-control" placeholder="'-','of'" @input="handleInput('combiner')" :light="true" />
                    </BCol>
                    <BCol lg="12">
                        <div class="table-responsive mt-n2 mb-2">
                            <table class="table table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="fs-11">
                                        <th class="fs-14 text-primary align-middle">LIST OF CAMPUSES ({{ form.campuses.length }} campus)</th>
                                        <th>
                                            <b-button @click="addCampus" variant="primary" class=" float-end btn-sm">Add Campus</b-button>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </BCol>
                </BRow>

                <BRow class="g-3 mt-n1" v-for="(campus, index) in form.campuses" :key="index">
                    <BCol lg="2" class="mt-1">
                        <InputLabel :value="(index === 0) ? 'Main Campus Name' : 'Campus Name'" :message="form.errors['campuses.'+index+'.name']"/>
                        <TextInput v-model="campus.name" type="text" class="form-control" placeholder="Please enter campus" @input="handleInput('campus')" :light="true" />
                    </BCol>
                    <BCol lg="2" class="mt-1">
                        <InputLabel value="Grading" :message="form.errors['campuses.'+index+'.grading_id']"/>
                        <Multiselect :options="dropdowns.gradings" v-model="campus.grading_id" label="name" :searchable="true" placeholder="Select Grading" />
                    </BCol>
                    <BCol lg="2" class="mt-1">
                        <InputLabel value="Term type" :message="form.errors['campuses.'+index+'.term_id']"/>
                        <Multiselect :options="dropdowns.terms" v-model="campus.term_id" label="name" :searchable="true" placeholder="Select Term" />
                    </BCol>
                    <BCol lg="2" class="mt-1">
                        <InputLabel value="Agency" :message="form.errors['campuses.'+index+'.agency_id']"/>
                        <Multiselect :options="dropdowns.agencies" v-model="campus.agency_id" label="name" :searchable="true" placeholder="Select Agency" />
                    </BCol>
                    <BCol lg="4" class="mt-1">
                        <div class="d-flex">
                            <div style="width: 100%;">
                                <InputLabel value="Address" :message="form.errors['campuses.'+index+'.address']"/>
                                <TextInput readonly v-model="campus.address" type="text" class="form-control" placeholder="Please enter address" @input="handleInput('campus')" :light="true" />
                            </div>
                            <div class="flex-shrink-0">
                                <b-button @click="addLocation(index)" style="margin-top: 20px;" variant="light" class="waves-effect waves-light ms-1"><i class="ri-map-pin-fill"></i></b-button>
                                <b-button @click="confirmRemoveCampus(index)" style="margin-top: 20px;" class="waves-effect waves-light ms-1 btn-soft-danger"><i class="ri-delete-bin-fill"></i></b-button>
                            </div>
                        </div>
                    </BCol>
                </BRow>
                <b-button @click="submit('ok')" class="float-end mt-2" variant="primary" :disabled="form.processing" block>Submit</b-button>
            </form>
        </div>
    </div>
    <Location :regions="dropdowns.regions" @submit="handleSubmit" ref="location"/>
</template>
<script>
import _ from 'lodash';
import Location from './Modals/Location.vue';
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, Multiselect, InputLabel, TextInput, Location },
    props:['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                shortcut: null,
                combiner: null,
                class_id: null,
                campuses: [{ name: '', agency_id: null, grading_id: null, term_id: '', address: '', info: '', region: '', province: '', municipality: '',barangay: '' }],
                option: 'school'
            }),
            index: null
        }
    },
    methods: { 
        submit(){
            if(this.editable){
                this.form.put('/management/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.form.reset();
                    },
                });
            }else{
                this.form.post('/management',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.form.reset();
                    },
                });
            }
        },
        handleSubmit(data) {
            const address = data.address;
            const index = data.index;
            const form = data.form;

            if (index !== undefined) {
                this.form.campuses[index].address = address;
                this.form.campuses[index].info = data.form.info;
                this.form.campuses[index].region = data.form.region;
                this.form.campuses[index].province = data.form.province;
                this.form.campuses[index].municipality = data.form.municipality;
                this.form.campuses[index].barangay = data.form.barangay;
            }
        },
        addLocation(index){
            const campus = this.form.campuses[index];
            if (campus.address) {
                this.$refs.location.update(index,this.form.campuses[index]);
            }else{
                this.$refs.location.show(index);
            }
        },
        addCampus() {
            this.form.campuses.push({ 
                name: '', 
                grading_id: null, 
                term_id: null, 
                agency_id: null, 
                address: '', 
                region: '', 
                province: '', 
                municipality: '',
                barangay: '' 
            });
        },
        confirmRemoveCampus(index) {
            if (confirm("Are you sure you want to remove this campus?")) {
                this.removeCampus(index);
            }
        },
        removeCampus(index){
            this.form.campuses.splice(index, 1);
        },
    }
}
</script>