<template>
    <b-modal v-if="analysis" v-model="showModal" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" title="View Analysis" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>
        
      
        <div class="ms-2 mt-n2">
            <h5 class="modal-title ms-n2 fs-16">{{analysis.code}} - <span class="text-muted">{{analysis.sample}}</span></h5>
            <div class="hstack gap-3 flex-wrap mt-0 ms-n2 mb-n2">
                <div class="text-primary">Lab Analyst :
                    <span class="text-body fw-semibold text-muted fs-13">{{(analysis.analyst == '-') ? 'Not yet started' : analysis.analyst}}</span>
                </div>
            </div>
        </div>
        <hr class="text-muted"/>
        <div class="row g-2 mt-n2">
             <div class="col-md-12">
                <div class="form-floating">
                    <input type="text" v-model="analysis.testname" class="form-control" readonly>
                    <label>Testname</label>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="form-floating">
                    <input type="text" v-model="analysis.method" class="form-control" readonly>
                    <label>Method</label>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="form-floating">
                    <input type="text" v-model="analysis.fee" class="form-control" readonly>
                    <label>Fee</label>
                </div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="form-floating">
                    <input type="text" v-model="analysis.reference" class="form-control" readonly>
                    <label>Reference</label>
                </div>
            </div>
            <div class="col-md-12">
                <hr class="text-muted mt-1 mb-1"/>
            </div>
            <div class="col-md-6 mt-2">
                <div class="form-floating">
                    <input type="text" v-model="analysis.start_at" class="form-control" readonly>
                    <label>Start At</label>
                </div>
            </div>
            <div class="col-md-6 mt-2">
                <div class="form-floating">
                    <input type="text" v-model="analysis.end_at" class="form-control" readonly>
                    <label>End At</label>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Close</b-button>
            <!-- <b-button variant="primary" block>Confirm</b-button> -->
        </template>
    </b-modal>
</template>
<script>
import InputLabel from '@/Shared/Components/Forms/InputLabel.vue';
import TextInput from '@/Shared/Components/Forms/TextInput.vue';
import Textarea from '@/Shared/Components/Forms/Textarea.vue';
export default {
    components: { InputLabel, TextInput, Textarea },
    data(){
        return {
            currentUrl: window.location.origin,
            analysis: null,
            showModal: false
        }
    },
    methods: { 
        show(data){
            this.analysis = data;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>