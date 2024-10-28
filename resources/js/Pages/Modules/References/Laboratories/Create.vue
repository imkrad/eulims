<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Agency' : 'Create Agency'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <div>
                        <h6 class="mb-1">General Information</h6>
                        <p class="text-muted fs-11 mt-n1">Please ensure the agency information is accurate and valid. </p>
                    </div>
                    <div>
                        <BRow class="g-3">
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                            <BCol lg="12" class="mt-1">
                                <InputLabel value="Name" :message="form.errors.name"/>
                                <TextInput v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('name')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel value="Code" :message="form.errors.code"/>
                                <TextInput v-model="form.code" type="text" class="form-control" placeholder="Please enter code" @input="handleInput('code')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel value="Acronym" :message="form.errors.acronym"/>
                                <TextInput v-model="form.acronym" type="text" class="form-control" placeholder="Please enter acronym" @input="handleInput('acronym')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel value="Avatar" :message="form.errors.avatar"/>
                                <TextInput v-model="form.avatar" type="text" class="form-control" placeholder="Please enter avatar" @input="handleInput('avatar')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel value="Region" :message="form.errors.region_code"/>
                                <Multiselect :options="regions" v-model="form.region_code" label="name" :searchable="true" placeholder="Select Region" />
                            </BCol>
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n3"/></BCol>
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
                name: null,
                code: null,
                acronym: null,
                avatar: 'avatar.jpg',
                region_code: null,
                option: 'agency'
            }),
            provinces: [],
            showModal: false,
            editable: false
        }
    },
    methods: { 
        show(){
            this.form.reset();
            this.showModal = true;
        },
        update(data){
            this.form.name = data.name;
            this.form.code = data.code;
            this.form.acronym = data.acronym;
            this.form.avatar = data.avatar;
            this.is_active = data.is_active;
            this.form.region_code = data.region.code;
            this.editable = true;
            this.showModal = true;
        },
        submit(){
            if(this.editable){
                this.form.put('/references/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.form.reset();
                        this.hide();
                    },
                });
            }else{
                this.form.post('/references',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.form.reset();
                        this.hide();
                        this.$emit('success',true);
                    },
                });
            }
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