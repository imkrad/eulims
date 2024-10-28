<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" title="Update Municipality" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <div>
                        <h6 class="mb-1">General Information</h6>
                        <p class="text-muted fs-11 mt-n1">Please ensure the municipality information is accurate and valid. </p>
                    </div>
                    <div>
                        <BRow class="g-3">
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                            <BCol lg="12" class="mt-1">
                                <InputLabel for="name" value="Name" :message="form.errors.name"/>
                                <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('firstname')" :light="true" />
                            </BCol>
                            <BCol lg="12" class="mt-0">
                                <InputLabel for="oldname" value="Old name" :message="form.errors.old_name"/>
                                <TextInput id="oldname" v-model="form.old_name" type="text" class="form-control" placeholder="Please enter oldname" @input="handleInput('firstname')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel for="district" value="District" :message="form.errors.district"/>
                                <TextInput id="district" v-model="form.district" type="text" class="form-control" placeholder="Please enter district" @input="handleInput('firstname')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel for="zip" value="Zipcode" :message="form.errors.zipcode"/>
                                <TextInput id="zip" v-model="form.zipcode" type="text" class="form-control" placeholder="Please enter zipcode" @input="handleInput('firstname')" :light="true" />
                            </BCol>
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel for="district" value="Region"/>
                                <Multiselect :options="regions" object v-model="filter.region" label="name" :searchable="true" placeholder="Select Region" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel for="district" value="Province"/>
                                <Multiselect :options="provinces" object v-model="form.province_code" label="name" :searchable="true" placeholder="Select Province" />
                            </BCol>
                        </BRow>
                    </div>    
                </BCol>
            </BRow>
        </form>
          <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import { useForm } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: {InputLabel, TextInput, Multiselect },
    props: ['regions'],
    data(){
        return {
            currentUrl: window.location.origin,
            form: useForm({
                id: null,
                code: null,
                name: null,
                old_name: null,
                district: null,
                zipcode: null,
                is_chartered: null,
                province_code: null,
                option: 'municipality'
            }),
            provinces: [],
            filter : {
                region : null
            },
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(data){
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.old_name = data.old_name;
            this.form.district = data.district;
            this.form.zipcode = data.zipcode;
            this.form.is_chartered = data.is_chartered;
            this.filter.region = this.transform(data.province.region);
            this.form.province_code = this.transform(data.province);
            this.showModal = true;
        },
        transform(data) {
            return {
                value: data.code,
                ...data,
            };
        },
        submit(){
            this.form.put('/references/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.form.reset();
                    this.hide();
                }
            });
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