<template>
    <b-modal v-if="selected" v-model="showModal" style="--vz-modal-width: 750px;" header-class="p-3 bg-light" title="View Fee" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
          <div class="ms-2 mt-0">
            <h5 class="modal-title ms-n2 fs-16">{{selected.type.name}} ({{selected.type.others}})</h5>
            <div class="hstack gap-3 flex-wrap mt-0 ms-n2 mb-n2">
                <div class="text-primary">
                    <span class="text-body text-muted fs-13">{{selected.laboratory.member.name}}</span>
                </div>
            </div>
        </div>
        <div class="row g-2 mt-n2">
            <div class="col-md-12 mt-0 mb-n2">
                <hr class="text-muted"/>
            </div>
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" :value="selected.sampletype.name" class="form-control" readonly>
                    <label>Sampletype</label>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="form-floating">
                    <input type="text" :value="selected.testname.name" class="form-control" readonly>
                    <label>Testname</label>
                </div>
            </div>
            <div class="col-md-12 mt-0 mb-n2">
                <hr class="text-muted"/>
            </div>
            <div class="col-md-12">
                <div class="form-floating">
                    <input type="text" :value="selected.method.method.name" class="form-control" readonly>
                    <label>Method</label>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-floating">
                    <input type="text" :value="refes" class="form-control" readonly>
                    <label>Reference</label>
                </div>
            </div>
             <div class="col-md-12">
                <div class="form-floating">
                    <input type="text" :value="selected.method.fee" class="form-control" readonly>
                    <label>Fee</label>
                </div>
            </div>
        </div> 
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button @click="submit('ok')" variant="primary" :disabled="form.processing" block>Submit</b-button>
        </template>
    </b-modal>
</template>
<script>
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
export default {
    components: { InputLabel, TextInput },
    data(){
        return {
            currentUrl: window.location.origin,
            selected: null,
            form: {},
            showModal: false
        }
    },
    computed: {
        refes() {
            const short = (this.selected.method.reference.short) ? '('+this.selected.method.reference.short+')' : '';
            const name = this.selected.method.reference.name;
            return name + short;
        }
    },
    methods: { 
        show(data){
            this.selected = data;
            this.showModal = true;
        },
        submit(){
            axios.post('/services', this.form)
            .then((response) => {
                this.$emit('selected',response.data);
                this.hide();
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                     console.log(this.errors);
                }
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>