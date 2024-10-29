<template>
    <b-modal v-model="showModal" :title="selected.code"  style="--vz-modal-width: 1000px;" header-class="p-3 bg-light" class="v-modal-custom" modal-class="zoomIn" centered no-close-on-backdrop>    
        <div class="row mb-2">
            <div class="col-sm-4">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-0">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                    class="ri-qr-code-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted fs-11 mb-0">Sample Code :</p>
                            <h5 class="fs-12 mb-0">{{selected.code}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-0">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-award-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted fs-11 mb-0">Sample name :</p>
                            <h5 class="fs-12 mb-0">{{selected.name}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="p-1 border border-dashed rounded">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-0">
                            <div class="avatar-title rounded bg-transparent text-primary fs-24"><i
                                    class="ri-calendar-todo-fill"></i></div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted fs-11 mb-0">Due At:</p>
                            <h5 class="fs-12 mb-0">{{selected.tsr.due_at}}</h5>
                        </div>
                    </div>
                </div>
            </div>
          
            <div class="col-sm-12"><hr class="text-muted"/></div>
            <b-col lg>
            <div class="input-group mb-2">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" placeholder="Search Request" class="form-control" style="width: 45%;">
                
            </div>
        </b-col>
            <div class="col-sm-12">
                <!-- {{ selected.lists }} -->
                
                <div v-if="selected.analyses" class="table-responsive mt-0 mb-0">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr class="fs-11">
                                <th class="text-center" width="5%" v-if="status!== 'Completed'">
                                    <input class="form-check-input fs-16" v-model="mark" type="checkbox" value="option" />
                                </th>
                                <th class="text-center" width="5%" v-else>#</th>
                                <th width="24%">Test Name</th>
                                <th class="text-center" width="47%">Method Reference</th>
                                <th class="text-center" width="12%">Fee</th>
                                <th class="text-center" width="12%"></th>
                            </tr>
                        </thead>
                    </table>
                    <simplebar data-simplebar style="max-height: calc(100vh - 500px);">
                        <table class="table table-nowrap align-middle mb-0">
                            <tbody>
                                <tr v-for="(list,index) in selected.analyses" v-bind:key="index" class="fs-11" :class="(list.selected) ? 'table-info' : ''">
                                    <td width="5%" class="text-center fs-14" v-if="status == 'Pending'"> 
                                        <input v-if="list.status.name !== 'Completed'" type="checkbox" v-model="list.selected" class="form-check-input" />
                                        <i v-else class="text-success ri-checkbox-circle-fill fs-18"></i>
                                    </td>
                                    <td width="5%" class="text-center fs-14" v-else-if="list.analyst_id === $page.props.user.data.id"> 
                                        <input v-if="list.status.name !== 'Completed'" type="checkbox" v-model="list.selected" class="form-check-input" />
                                        <i v-else class="text-success ri-checkbox-circle-fill fs-18"></i>
                                    </td>
                                    <td  width="5%" class="text-center fs-14" v-else>
                                        <span class="text-muted">-</span>
                                    </td>
                                    <td  width="24%">
                                        <h5 class="fs-11 mb-0">{{list.testname}}</h5>
                                        <p class="text-muted mb-0">{{list.sample}}</p>
                                    </td>
                                    <td  width="47%" class="text-center">
                                        <h5 class="fs-11 mb-0">{{list.method}}</h5>
                                        <p class="text-muted mb-0">{{list.reference}}</p>
                                    </td>
                                    <td  width="12%" class="text-center">{{list.fee}}</td>
                                    <td  width="12%" class="text-center">
                                        <span :class="'badge '+list.status.color+' '+list.status.others">{{list.status.name}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </simplebar>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="hide()" variant="light" block>Cancel</b-button>
            <b-button v-if="status === 'Pending'" @click="save(11,'start')" variant="primary" :disabled="form.processing" block>Start Analysis</b-button>
            <b-button v-if="status === 'Ongoing'" @click="save(12,'end')" variant="primary" :disabled="form.processing" block>End Analysis</b-button>
        </template>
    </b-modal>
    <Save @hide="hide" ref="save"/>
</template>
<script>
import simplebar from "simplebar-vue";
import Save from './Save.vue';
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
export default {
    components: { Multiselect, Save, simplebar },
    data(){
        return {
            selected: {
                tsr: {},
                analyses: []
            },
            filterCode: null,
            status: null,
            form: {},
            showModal: false,
            mark: false
        }
    },
    watch: {
        mark(){
            if(this.mark){
                this.selected.analyses.forEach(item => {
                    if(this.status == 'Ongoing'){
                        if (item.analyst_id === this.$page.props.user.data.id) {
                            item.selected = true;
                        }
                    }else{
                        item.selected = true;
                    }
                });
            }else{
                this.selected.analyses.forEach(item => {
                    item.selected = false;
                });
            }
        }
    },
    computed: {
        tests() {
            return this.selected.analyses
                .filter(item => item.selected)
                .map(item => item.id);
        }
    },
    methods : {
        show(data,status) {
            this.status = status;
            this.selected = data;
            this.mark = null;
            this.showModal = true;
        },
        save(status,type){
            this.form = this.$inertia.form({
                tsr_id: this.selected.tsr_id,
                id: this.tests,
                status_id: status,
                start_at: null,
                end_at: null,
                option: type
            });
            this.$refs.save.show(this.form);
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        hide(){
            this.$emit('update',true);
            this.mark = null;
            this.showModal = false;
        },
    }
}
</script>
<style>
.thead-fixed {
   position: sticky;
   top: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
</style>