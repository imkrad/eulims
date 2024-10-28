<template>
    <!-- <div class="row mt-n2">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle fs-3 text-primary">
                                <i class="ri-qr-code-fill align-middle"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">TSR CODE</p>
                            <h4 class="mb-0 fs-14"><span class="counter-value">{{(selected.code) ? selected.code : 'Not yet available'}}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle fs-3 text-primary">
                                <i class="ri-hand-coin-fill align-middle"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Samples Submitted</p>
                            <h4 class="mb-0 fs-14"><span class="counter-value">{{selected.samples.length}}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-none border">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0"><span
                                class="avatar-title bg-light rounded-circle fs-3 text-primary"><i
                                    class="ri-service-fill align-middle"></i></span></div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Status</p>
                            <h4 class="mb-0 fs-14">
                                <span :class="'badge '+selected.status.color">{{selected.status.name}}</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <b-row class="g-2 mt-n3">
        <b-col lg>
            <div class="input-group mb-2">
                <span class="input-group-text fw-semibold fs-12"> {{(samples.length === selected.samples.length) ? 'All' : samples.length}} samples are selected. </span>
                <input type="text"  placeholder="Search Request" class="form-control" style="width: 55%;">
                <span v-if="selected.laboratory_type === 11" @click="openService(id)" class="input-group-text" v-b-tooltip.hover title="Add Service" style="cursor: pointer;"> 
                    <i class="ri-add-circle-fill text-primary search-icon"></i>
                </span>
                <span v-if="selected.status.name == 'Pending'" @click="openAnalysis()" class="input-group-text" v-b-tooltip.hover title="Add Analysis" style="cursor: pointer;"> 
                    <i class="ri-flask-fill text-primary search-icon"></i>
                </span>
                <b-button v-if="selected.status.name == 'Pending'" @click="openSample()" type="button" variant="primary">
                    <i class="ri-add-circle-fill align-bottom me-1"></i>Add Sample
                </b-button>
            </div>
        </b-col>
    </b-row>
    <!-- <hr class="text-muted mt-3"/> -->
    <!-- <div class="d-flex align-items-center mb-3">
        <h5 class="flex-grow-1 fs-16 mb-0" id="filetype-title">Samples</h5>
        <div class="flex-shrink-0">
            <button class="btn btn-success btn-md create-folder-modal text-nowrap flex-shrink-0" type="button">
                <div class="btn-content"><i class="ri-add-line align-bottom me-1"></i> Create File</div>
            </button>
        </div>
    </div> -->
    <!-- <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">Samples</span></td>
                <td><b-button variant="danger">Add</b-button></td>
            </tr>
        </tbody>
    </table> -->
    <div class="table-responsive">
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th width="5%" class="text-center">
                        <input class="form-check-input fs-16" v-model="mark" type="checkbox" value="option" />
                    </th>
                    <th width="20%">Sample Name</th>
                    <th width="65%">Description</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in selected.samples" v-bind:key="index">
                    <td width="5%" class="text-center">
                        <input type="checkbox" v-model="list.selected" class="form-check-input" />
                    </td>
                    <td width="20%">
                        <h5 class="fs-13 mb-0 text-dark">{{list.name}}</h5>
                        <p class="fs-12 text-muted mb-0">{{(list.code) ? list.code : 'Not yet available'}}</p>
                    </td>
                    <td width="65%"><i>{{list.customer_description}}</i>, {{list.description}}</td>
                    <td width="10%" class="text-end">
                        <b-button @click="openView(list)" variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                            <i class="ri-eye-fill align-bottom"></i>
                        </b-button>
                        <b-button v-if="selected.status.name == 'Pending'" @click="openDelete(list)" variant="soft-danger" v-b-tooltip.hover title="Delete" size="sm">
                            <i class="ri-delete-bin-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- <hr class="text-muted mt-3"/> -->
    <!-- <div class="alert alert-primary alert-dismissible alert-label-icon rounded-label" role="alert">
        <i class="ri-user-smile-line label-icon"></i><strong>Parameters</strong>  
    </div> -->
     <!-- <table class="table table-bordered">
        <tbody>
            <tr>
                <td style="border-right: none; border-left: none;"><span class="fw-semibold fs-12 ms-2">Parameters</span></td>
            </tr>
        </tbody>
    </table> -->
    <!-- <hr class="text-muted"/> -->
    <b-row class="g-2 mt-2 mb-2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text fw-semibold fs-12"> {{(samples.length === selected.samples.length) ? 'All' : samples.length}} samples are selected. </span>
                <input type="text"  placeholder="Search Request" class="form-control" style="width: 55%;">
                <span v-if="selected.laboratory_type === 11" @click="openService(id)" class="input-group-text" v-b-tooltip.hover title="Add Service" style="cursor: pointer;"> 
                    <i class="ri-add-circle-fill text-primary search-icon"></i>
                </span>
                <span v-if="selected.status.name == 'Pending'" @click="openAnalysis()" class="input-group-text" v-b-tooltip.hover title="Add Analysis" style="cursor: pointer;"> 
                    <i class="ri-flask-fill text-primary search-icon"></i>
                </span>
                <b-button v-if="selected.status.name == 'Pending'" @click="openSample()" type="button" variant="primary">
                    <i class="ri-add-circle-fill align-bottom me-1"></i>Add Sample
                </b-button>
            </div>
        </b-col>
    </b-row>
    <!-- <hr class="text-muted mt-3"/> -->
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th class="text-center" width="5%">#</th>
                    <th width="20%">Test Name</th>
                    <th class="text-center" width="50%">Method Reference</th>
                    
                    <!-- <th class="text-center" width="20%">Analyst</th> -->
                    <th class="text-center" width="12%">Fee</th>
                    <th class="text-center" width="13%">Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in analyses" v-bind:key="index">
                    <td class="text-center"> 
                        {{index + 1}}
                    </td>
                    <td>
                        <h5 class="fs-13 mb-0 text-dark">{{list.testname}}</h5>
                        <p class="fs-12 text-muted mb-0">{{(list.sample.code) ? list.sample.code : 'Not yet available'}}</p>
                    </td>
                    <td class="text-center">
                        <h5 class="fs-12 mb-0">{{list.method}}</h5>
                        <p class="fs-11 text-muted mb-0">{{list.reference}}</p>
                    </td>
                    <!-- <td class="text-center">{{list.analyst}}</td> -->
                    <td class="text-center">{{list.fee}}</td>
                    <td class="text-center">
                        <span :class="'badge '+list.status.color+' '+list.status.others">{{list.status.name}}</span>
                    </td>
                    <td>
                        <b-button @click="openView(list)" variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                            <i class="ri-eye-fill align-bottom"></i>
                        </b-button>
                        <b-button v-if="selected.status.name == 'Pending'" @click="openDelete(list)" variant="soft-danger" v-b-tooltip.hover title="Delete" size="sm">
                            <i class="ri-delete-bin-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <Delete ref="delete"/>
    <Sample ref="sample"/>
    <Analysis ref="analysis"/>
    <Service :services="services" ref="service"/>
</template>
<script>
import Delete from '../Modals/Delete.vue';
import Sample from '../Modals/Sample.vue';
import Service from '../Modals/Service.vue';
import Analysis from '../Modals/Analysis.vue';
export default {
    components: { Delete, Sample, Service, Analysis },
    props:['selected','services','analyses'],
    data(){
        return {
            samples : [],
            mark: false,
        }
    },
    watch: {
        mark(){
            if(this.mark){
                this.selected.samples.forEach(item => {
                    item.selected = true;
                    this.samples.push(item.id);
                });
            }else{
                this.selected.samples.forEach(item => {
                    item.selected = false;
                });
                this.samples = [];
            }
        }
    },
    methods: {
        openDelete(data){
            this.$refs.delete.show(data,this.selected.id);
        },
        openSample(){
            this.$refs.sample.show(this.selected.id,this.selected.laboratory_type);
        },
        openAnalysis(){
            this.$refs.analysis.show(this.samples,this.selected.laboratory_type);
        },
    }
}
</script>