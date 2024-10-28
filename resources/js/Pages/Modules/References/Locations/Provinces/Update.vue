<template>
    <b-modal v-model="showModal" header-class="p-3 bg-light" :title="(editable) ? 'Update Province' : 'Create Province'" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        <form class="customform">
            <BRow>
                <BCol lg="12">
                    <div>
                        <h6 class="mb-1">General Information</h6>
                        <p class="text-muted fs-11 mt-n1">Please ensure the province information is accurate and valid. </p>
                    </div>
                    <div>
                        <BRow class="g-3">
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n4"/></BCol>
                            <BCol lg="12" class="mt-1">
                                <InputLabel for="name" value="Name" :message="form.errors.name"/>
                                <TextInput id="name" v-model="form.name" type="text" class="form-control" placeholder="Please enter name" @input="handleInput('firstname')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel for="code" value="Code" :message="form.errors.code"/>
                                <TextInput id="code" v-model="form.code" type="text" class="form-control" placeholder="Please enter code" @input="handleInput('code')" :light="true" />
                            </BCol>
                            <BCol lg="6" class="mt-0">
                                <InputLabel for="oldname" value="Old name" :message="form.errors.old_name"/>
                                <TextInput id="oldname" v-model="form.old_name" type="text" class="form-control" placeholder="Please enter oldname" @input="handleInput('firstname')" :light="true" />
                            </BCol>
                            <BCol lg="12"><hr class="text-muted mt-n1 mb-n3"/></BCol>
                            <BCol lg="12" class="mt-0">
                                <InputLabel value="Region" :message="form.errors.region_code"/>
                                <Multiselect :options="regions" object v-model="form.region_code" label="name" :searchable="true" placeholder="Select Region" />
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
                region_code: null,
                option: 'province'
            }),
            provinces: [],
            showModal: false,
            editable: false
        }
    },
    methods: { 
        create(){
            this.form.reset();
            this.editable = false;
            this.showModal = true;
        },
        show(data){
            this.form.id = data.id;
            this.form.code = data.code;
            this.form.name = data.name;
            this.form.old_name = data.old_name;
            this.form.region_code = this.transform(data.region);
            this.editable = true;
            this.showModal = true;
        },
        transform(data) {
            return {
                value: data.code,
                ...data,
            };
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