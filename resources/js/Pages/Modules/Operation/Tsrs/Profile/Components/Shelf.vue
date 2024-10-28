<template>
    <ul class="nav nav-pills nav-custom nav-custom-light mb-3 mt-0" role="tablist">
        <li class="nav-item">
            <b-link class="nav-link active" data-bs-toggle="tab" href="#samples" role="tab">
                Samples
            </b-link>
        </li>
        <li class="nav-item">
            <b-link class="nav-link" data-bs-toggle="tab" href="#groups" role="tab">
                Cycles
            </b-link>
        </li>
         <li class="nav-item">
            <b-link class="nav-link" data-bs-toggle="tab" href="#childs" role="tab">
                TSR's
            </b-link>
        </li>
    </ul>
    <div class="tab-content text-muted">
        <div class="tab-pane active" id="samples" role="tabpanel">
            <b-row class="g-2">
                <b-col lg>
                    <div class="input-group mb-2">
                        <span class="input-group-text fw-semibold fs-12" style="width: 100px;">{{selected.samples.length}} Samples</span>
                        <input type="text"  placeholder="Search Request" class="form-control" style="width: 55%;">
                        <span v-if="selected.laboratory_type === 3 && selected.service == null && selected.status.name == 'Pending'" @click="openService()" class="input-group-text" v-b-tooltip.hover title="Add Service" style="cursor: pointer;"> 
                            <i class="ri-add-circle-fill text-primary search-icon"></i>
                        </span>
                        <b-button v-if="selected.status.name == 'Pending'" @click="openSample()" type="button" variant="primary" :disabled="(mark) ? true : false">
                            <i class="ri-add-circle-fill align-bottom me-1"></i>Sample
                        </b-button>
                    </div>
                </b-col>
            </b-row>
            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead class="table-light thead-fixed">
                        <tr class="fs-11">
                            <th v-if="selected.status.name == 'Pending'" width="4%" class="text-center">
                                <input class="form-check-input fs-16" v-model="mark" type="checkbox" value="option" />
                            </th>
                            <th :class="(selected.status.name == 'Pending') ? '' : 'text-center'" width="5%">#</th>
                            <th width="20%">Sample Name</th>
                            <th width="63%">Description</th>
                            <th v-if="selected.status.name != 'Pending'" width="4%" class="text-center">Status</th>
                            <th width="7%"></th>
                        </tr>
                    </thead>
                    <tbody v-if="selected.samples.length > 0">
                        <template  v-for="(list,index) in selected.samples" v-bind:key="index">
                        <tr>
                            <td v-if="selected.status.name == 'Pending'"  width="4%" class="text-center">
                                <input type="checkbox" v-model="list.selected" class="form-check-input" />
                            </td>
                            <td :class="(selected.status.name == 'Pending') ? '' : 'text-center'" width="3%">{{index+1}}</td>
                            <td width="20%">
                                <h5 class="fs-13 mb-0 fw-semibold text-primary">{{(list.code) ? list.code : 'Not yet available'}}</h5>
                                <p class="fs-13 text-muted mb-0">{{list.name}}</p>
                            </td>
                            <td width="63%" class="fs-12" style=" white-space: normal;overflow: hidden; text-overflow: ellipsis; max-width: 150px;">
                                <i>{{list.customer_description}}</i>, {{list.description}}
                            </td>
                            <td v-if="selected.status.name != 'Pending'" width="4%" class="text-center">
                                <span class="fs-12" v-if="list.analyses.filter(item => item.status.name == 'Completed').length != list.analyses.length">{{list.analyses.filter(item => item.status.name == "Completed").length}} / {{list.analyses.length}}</span>
                                <span v-else><i class="ri-checkbox-circle-fill text-success fs-18" v-b-tooltip.hover :title="list.analyses.filter(item => item.status.name == 'Completed').length+'/'+list.analyses.length"></i></span>
                            </td>
                            <td width="7%" class="text-end">
                                <b-button v-if="selected.status.name == 'Pending'" @click="openDeleteSample(list)" variant="soft-danger" v-b-tooltip.hover title="Delete" size="sm">
                                    <i class="ri-delete-bin-fill align-bottom"></i>
                                </b-button>
                            </td>
                        </tr>
                        <tr v-if="list.analyses.length > 0" class="bg-info-subtle">
                                <td colspan="5">
                                    <table class="table table-nowrap border align-middle mb-0">
                                        <thead class="table-light thead-fixed">
                                            <tr class="fs-10">
                                                <th class="text-center" width="5%">#</th>
                                                <th width="20%">Test Name</th>
                                                <th class="text-center" width="50%">Method Reference</th>
                                                <th class="text-center" width="12%">Fee</th>
                                                <th class="text-center" width="13%">Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="list.analyses.length > 0">
                                            <tr v-for="(list,index) in list.analyses" v-bind:key="index" class="bg-light-subtle">
                                                <td class="text-center"> 
                                                    {{index + 1}}
                                                </td>
                                                <td>
                                                    <h5 class="fs-12 mb-0">{{list.testname}}</h5>
                                                </td>
                                                <td class="text-center">
                                                    <h5 class="fs-12 mb-0">{{list.method}}</h5>
                                                    <p class="fs-11 text-muted mb-0">{{list.reference}}</p>
                                                </td>
                                                <td class="text-center">
                                                    <h5 class="fs-12 mb-0">{{list.fee}}</h5>
                                                    <span v-if="list.addfee" class="text-muted fs-11">(+ {{list.addfee.total}} fee)</span>
                                                </td>
                                                <td class="text-center">
                                                    <span :class="'badge '+list.status.color+' '+list.status.others">{{list.status.name}}</span>
                                                </td>
                                                <td>
                                                    <b-button @click="openAdditional(list.additional,list.id)" v-if="list.additional != null && list.addfee == null" variant="soft-success" class="me-1" v-b-tooltip.hover title="Add" size="sm">
                                                        <i class="ri-add-circle-fill align-bottom"></i>
                                                    </b-button>
                                                    <b-button @click="openViewAnalysis(list)" variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                                        <i class="ri-eye-fill align-bottom"></i>
                                                    </b-button>
                                                    <b-button v-if="selected.status.name == 'Pending' || selected.status.name == 'For Payment' && analyses.length > 1" @click="openDeleteAnalysis(list)" variant="soft-danger" v-b-tooltip.hover title="Delete" size="sm">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                                    </b-button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5" class="text-center">No analysis found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="groups" role="tabpanel">
            <div class="d-grid gap-2" >
                <b-button v-if="selected.status.name == 'Pending'" @click="openGroup()" type="button" variant="light">
                    <i class="ri-add-circle-fill align-bottom me-1"></i>Add Cycle
                </b-button>
            </div>  

            <div class="table-responsive mt-2">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light thead-fixed">
                        <tr class="fs-11">
                            <th class="text-center" width="4%">#</th>
                            <th class="text-center" width="13%">Sampling Days</th>
                            <th class="text-center" width="13%">Date</th>
                            <th class="text-center">Testnames</th>
                            <th class="text-center" width="13%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fs-11" v-for="(group, day) in groupedByDays" :key="day">
                            <td class="text-center" style="cursor: pointer;">
                                <i @click="openViewGroup(group,selected.customer,selected.payment,selected.id)" v-if="group.status_id == 24" class="ri-checkbox-circle-fill text-success fs-15" v-b-tooltip.hover title="Confirmed"></i>
                                <i @click="openViewGroup(group,selected.customer,selected.payment,selected.id)" v-else-if="group.status_id == 25" class="ri-checkbox-circle-fill text-info fs-15" v-b-tooltip.hover title="Unprocessed"></i>
                                <i @click="openViewGroup(group,selected.customer,selected.payment,selected.id)" v-else class="ri-close-circle-fill text-danger fs-15" v-b-tooltip.hover title="Pending"></i>
                            </td>
                            <td class="text-center">{{day}}</td>
                            <td class="text-center">{{group.date}}</td>
                            <td><span v-for="item in group.items" :key="item.id">{{item.testservice.testname.name}}, </span></td>
                            <td class="text-center">{{formatMoney(group.totalSum)}}</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="fs-11">
                            <th class="text-end text-muted" colspan="4">Grand Total</th>
                            <th class="text-center">{{formatMoney(grandTotal)}}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="childs" role="tabpanel">
            <div class="d-grid gap-2" >
                <b-button v-if="selected.status.name == 'Pending'" @click="openGroup()" type="button" variant="light">
                    <i class="ri-add-circle-fill align-bottom me-1"></i>Add Tsr
                </b-button>
            </div>  

            <div class="table-responsive mt-2">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                     <thead class="table-light thead-fixed">
                        <tr class="fs-11">
                            <th></th>
                            <th style="width: 65%;">TSR</th>
                            <th style="width: 15%;" class="text-center">Due At</th>
                            <th style="width: 15%;" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="fs-11" v-for="(list,index) in selected.children" v-bind:key="index">
                            <td class="text-center" style="cursor: pointer;"> #</td>
                            <td>{{list.child.code}}</td>
                            <td class="text-center">{{list.child.due_at}}</td>
                            <td class="text-center"><span :class="'badge '+list.child.status.color">{{list.child.status.name}}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <Sample ref="sample"/>
    <Delete ref="delete"/>
    <Group :laboratories="laboratories" ref="group"/>
    <ViewGroup ref="viewgroup"/>
</template>
<script>
import Group from '../Modals/Group.vue';
import Sample from '../Modals/Sample.vue';
import Delete from '../Modals/Delete.vue';
import ViewGroup from '../Modals/ViewGroup.vue';
export default {
    props:['selected','services','analyses','laboratories'],
    components: { Group, Sample, Delete, ViewGroup },
    data(){
        return {
            showAnalyses: true,
        }
    },
    computed: {
        groupedByDays() {
            return this.selected.groups.reduce((acc, item) => {
                const day = item.days;
                const date = item.date;

                if (!acc[day]) {
                acc[day] = {
                    date: date,
                    items: [],
                    totalSum: 0
                };
                }

                acc[day].items.push(item);
                acc[day].totalSum += parseFloat(item.total);

                return acc;
            }, {});
        },
        grandTotal() {
            return Object.values(this.groupedByDays).reduce((total, group) => {
                return total + group.totalSum;
            }, 0);
        },
    },
    methods: { 
        openGroup(){
            this.$refs.group.show(this.selected);
        },
        formatMoney(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return '₱'+val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        openSample(){
            this.$refs.sample.show(this.selected.id,this.selected.laboratory_type);
        },
        openDeleteSample(data){
            this.$refs.delete.show(data,this.selected.id,'sample');
        },
        openAnalysis(){
            (this.samples.length > 0) ? this.$refs.analysis.show(this.samples,this.selected.laboratory_type) : '';
        },
        openViewGroup(data,customer,payment,id){
            this.$refs.viewgroup.show(data,customer,payment,id);
        }
    }
}
</script>