<template>
    <b-modal v-model="showModal" style="--vz-modal-width: 1200px;" header-class="p-3 bg-light" :title="(!editable) ? 'Create Customer' : 'Edit Customer'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        
            <BRow>
                <BCol lg="12" class="mt-n1">
                    <Multiselect 
                    :create-option="true" 
                    :options="names" 
                    @search-change="fetchCustomer" 
                    v-model="form.customer" 
                    object
                    :searchable="true" 
                    label="name"
                    :message="form.errors.name" 
                    placeholder="Select Customer"/>
                    <div v-if="form.customer" class="mb-n2">
                        <div v-if="(typeof form.customer.value === 'string')" class="alert alert-success mt-2 p-2 fs-12" role="alert">
                            The inputted customer name is new. Please double-check the spelling.
                        </div>
                        <div v-if="(typeof form.customer.value === 'number')" class="alert alert-warning mt-2 p-2 fs-12" role="alert">
                            The customer name already exists. This will add a branch for the customer name.
                        </div>
                    </div>
                    <hr class="text-muted mt-3 mb-4"/>
                    <div class="mt-3">
                    <form class="customform">
                        <BRow>
                            <BCol lg="12">
                                <BRow class="g-3">
                                    <BCol lg="8"  style="margin-top: 13px; margin-bottom: -12px;" class="fs-12">Does the new customer represent a branch?</BCol>
                                    <BCol lg="4"  style="margin-top: 13px; margin-bottom: -12px;">
                                    <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio mb-3">
                                                    <input type="radio" id="customRadio1" class="custom-control-input me-2" :value="true" v-model="form.has_branches">
                                                    <label class="custom-control-label fw-normal fs-12" for="customRadio1">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio mb-3">
                                                    <input type="radio" id="customRadio2" class="custom-control-input me-2" :value="false" v-model="form.has_branches">
                                                    <label class="custom-control-label fw-normal fs-12" for="customRadio2">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </BCol>
                                    <BCol lg="12"><hr class="text-muted mt-n2"/></BCol>
                                </BRow>
                            </BCol>
                            <BCol lg="7">
                                <BRow class="g-3 mt-n2">
                                    <BCol lg="12" v-if="form.has_branches" class="mt-1 mb-n1">
                                        <InputLabel for="name" value="Branch" :message="form.errors.name"/>
                                        <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true"/>
                                    </BCol>
                                    <BCol lg="6" class="mt-1 mb-n1">
                                        <InputLabel for="email" value="Email" :message="form.errors.email"/>
                                        <TextInput id="email" v-model="form.email" type="email" class="form-control" placeholder="Please enter email" @input="handleInput('email')" :light="true"/>
                                    </BCol>
                                    <BCol lg="6" class="mt-1 mb-n1">
                                        <InputLabel for="contact_no" value="Mobile no." :message="form.errors.contact_no"/>
                                        <TextInput id="contact_no" v-model="form.contact_no" type="text" class="form-control" placeholder="Please enter contact" @input="handleInput('contact_no')" :light="true"/>
                                    </BCol>
                                    <BCol lg="6" class="mt-1 mb-1">
                                        <InputLabel for="classification_id" value="Classification" :message="form.errors.classification_id"/>
                                        <Multiselect :options="dropdowns.classes" label="name" :searchable="true" v-model="form.classification_id" placeholder="Select Classification" @input="handleInput('classification_id')"/>
                                    </BCol>
                                    <!-- <BCol :lg="(form.has_branches) ? 6 : 12" class="mt-1 mb-1">
                                        <InputLabel for="industry_id" value="Industry Type" />
                                        <Multiselect :options="dropdowns.industries" :searchable="true" v-model="form.industry_id" :message="form.errors.industry_id" placeholder="Select Industry"/>
                                        <InputError :message="form.errors.industry_id" />
                                    </BCol> -->
                                    <BCol lg="6" class="mt-1 mb-1">
                                        <InputLabel for="industry_id" value="Industry Type" :message="form.errors.industry_id"/>
                                        <Multiselect :options="industries" :searchable="true" label="name" object v-model="industry" placeholder="Select Industry" @input="handleInput('industry')" />
                                    </BCol>
                                    <BCol lg="12" class="mt-1 mb-1" v-if="subs.length > 0">
                                        <InputLabel for="industry_id" value="Industry Subtype" :message="form.errors.industry_id"/>
                                        <Multiselect :options="subs" :searchable="true" label="name" v-model="form.industry_id" placeholder="Select Industry" @input="handleInput('industry_id')" />
                                    </BCol>
                                    <BCol lg="12"><hr class="text-muted mt-0 mb-1"/></BCol>
                                    <BCol lg="6" class="mt-1">
                                        <InputLabel for="region" value="Region" :message="form.errors.region_code"/>
                                        <Multiselect :options="dropdowns.regions" label="name" v-model="form.region_code" :message="form.errors.region_code" placeholder="Select Region" @input="handleInput('region_code')"/>
                                    </BCol>
                                    <BCol lg="6" class="mt-1">
                                        <InputLabel for="province" value="Province" :message="form.errors.province_code"/>
                                        <Multiselect :options="provinces" :searchable="true" label="name" v-model="form.province_code" placeholder="Select Province" @input="handleInput('province_code')"/>
                                    </BCol>
                                    <BCol lg="6" class="mt-1">
                                        <InputLabel for="municipality" value="Municipality" :message="form.errors.municipality_code"/>
                                        <Multiselect :options="municipalities" :searchable="true" label="name" v-model="form.municipality_code" placeholder="Select Municipality" @input="handleInput('municipality_code')"/>
                                    </BCol>
                                    <BCol lg="6" class="mt-1">
                                        <InputLabel for="barangay" value="Barangay" :message="form.errors.barangay_code"/>
                                        <Multiselect :options="barangays" :searchable="true" label="name" v-model="form.barangay_code" placeholder="Select Barangay" @input="handleInput('barangay_code')"/>
                                    </BCol>
                                    <BCol lg="12" class="mt-1">
                                        <InputLabel for="address" value="Address" :message="form.errors.address"/>
                                        <TextInput id="address" v-model="form.address" type="text" class="form-control" placeholder="Please enter address" @input="handleInput('address')" :light="true"/>
                                    </BCol>
                                </BRow>
                            </BCol>
                            <BCol lg="5">
                                <div class="mt-2">
                                    <Map @set="handleCoordinates" ref="map" class="leaflet-map"/>
                                </div>
                            </BCol>
                        </Brow>
                    </form>
                    </div>    
                </BCol>
                <BCol lg="12">
                     <hr class="text-muted mt-3 mb-n2"/>
                </BCol>
            </BRow>
          <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import _ from 'lodash';
import { useForm } from '@inertiajs/vue3';
import Map from '../Components/Map.vue';
import Search from '../Components/Search.vue';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput, Multiselect, Map, Search },
    props: ['dropdowns','region'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                name: null,
                email: null,
                is_main: false,
                contact_no: null,
                classification_id: null,
                industry_id: null,
                has_branches: null,
                address: null,
                region_code: this.region,
                province_code: null,
                municipality_code: null,
                barangay_code: null,
                latitude: null,
                longitude: null,
                customer: null,
                name_id: null,
                option: 'customer'
            }),
            has_branch: false,
            names: [],
            industry: null,
            provinces: [],
            municipalities: [],
            barangays: [],
            showModal: false,
            editable: false,
            coordinates: {},
            subs: []
        }
    },
    watch: {
        'form.region_code'(){
            (this.form.region_code) ? this.fetchProvince(this.form.region_code) : '';
        },
        'form.province_code'(){
            (this.form.province_code) ? this.fetchMunicipality(this.form.province_code) : '';
        },
        'form.municipality_code'(){
            (this.form.municipality_code) ? this.fetchBarangay(this.form.municipality_code) : '';
        },
        'form.customer'(){
            if(this.form.customer){
               if(typeof this.form.customer.value === 'number'){
                    (this.form.customer.has_branches) ? this.has_branch = true : this.has_branch = false;
                    this.form.has_branches = (this.has_branch) ? true : false;
                    this.form.name_id = this.form.customer.value;
               }else if(typeof this.form.customer.value === 'string'){
                    this.form.has_branches = false;
                    this.has_branch = false;
               }    
            }
        },
        'form.has_branches'(){
            if(!this.form.has_branches){
                this.form.name = 'Main';
                this.form.is_main = true;
            }else{
                this.form.name = null;
                this.form.is_main = false;
            }
        },
        'industry'(){
            if(this.industry){
                if(this.industry.is_alone == 1){
                    this.form.industry_id = this.industry.value;
                    this.subs = [];
                }else{
                    this.subs = this.dropdowns.industries.filter(industry => industry.industry_id == this.industry.value);
                }
            }else{
                this.subs = [];
            }
        }
    },
    computed: {
        industries() {
            return this.dropdowns.industries.filter(industry => industry.is_main == 1);
        },
    },
    methods: { 
        handleCoordinates(coords) {
            this.coordinates = coords;
            this.form.longitude = this.coordinates.lng;
            this.form.latitude = this.coordinates.lat;
        },
        show(){
            this.$refs.map.view();
            this.fetchProvince(this.region);
            this.showModal = true;
        },
        submit(){
            this.form.post('/customers',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.$emit('message',true);
                    this.hide();
                },
            });
        },
        fetchCustomer(code){
            axios.get('/customers',{
                params: {
                    option: 'search',
                    keyword: code
                }
            })
            .then(response => {
                this.names = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchProvince(code){
            axios.get('/search',{
                params: {
                    option: 'provinces',
                    code: code
                }
            })
            .then(response => {
                this.provinces = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchMunicipality(code){
            axios.get('/search',{
                params: {
                    option: 'municipalities',
                    code: code
                }
            })
            .then(response => {
                this.municipalities = response.data;
            })
            .catch(err => console.log(err));
        },
        fetchBarangay(code){
            axios.get('/search',{
                params: {
                    option: 'barangays',
                    code: code
                }
            })
            .then(response => {
                this.barangays = response.data;
            })
            .catch(err => console.log(err));
        },
        handleInput(field) {
            this.form.errors[field] = false;
        },
        hide(){
            this.industry = null;
            this.form.reset();
            this.form.clearErrors();
            // this.form.member_id = null;
            // this.form.member_id = null;
            // this.form.region_code = null;
            // this.form.province_code = null;
            // this.form.municipality_code = null;
            // this.form.barangay_code = null;
            // this.form.type_id = null;
            //  form: useForm({
            //     id: null,
            //     name: null,
            //     email: null,
            //     is_main: false,
            //     contact_no: null,
            //     classification_id: null,
            //     industry_id: null,
            //     has_branches: null,
            //     address: null,
            //     region_code: this.region,
            //     province_code: null,
            //     municipality_code: null,
            //     barangay_code: null,
            //     latitude: null,
            //     longitude: null,
            //     customer: null,
            //     name_id: null,
            //     type: 'customer'
            // }),
            this.editable = false;
            this.showModal = false;
        }
    }
}
</script>