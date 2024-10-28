<template>
    <b-row class="g-2 mb-2 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="filter.keyword" placeholder="Search Request" class="form-control" style="width: 40%;">
                <input v-if="filter.datetype" type="date" v-model="filter.date" placeholder="Search Request" class="form-control" style="width: 100px;">
                <select v-model="filter.datetype" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 100px;">
                    <option :value="null" selected>Filter by date</option>
                    <option value="due_at" selected>Due Date</option>
                    <option value="created_at" selected>Request Date</option>
                </select>
                <select v-model="filter.laboratory" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 100px;">
                    <option :value="null" selected>Select Laboratory</option>
                    <option :value="list.value" v-for="list in dropdowns.laboratories" v-bind:key="list.id">{{list.name}}</option>
                </select>
                <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="bx bx-refresh search-icon"></i>
                </span>
                <b-button type="button" variant="primary" @click="openCreate">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                </b-button>
            </div>
        </b-col>
    </b-row>
    
    <div class="d-flex">
        <div class="flex-grow-1">
            <ul class="nav nav-tabs nav-tabs-custom nav-success border border-dashed border-end-0 border-start-0 fs-12" role="tablist">
                <li class="nav-item">
                    <BLink @click="viewStatus(10,null)" class="nav-link py-3 active text-primary" data-bs-toggle="tab" role="tab" aria-selected="true">
                    <i class="ri-apps-2-line me-1 align-bottom"></i> All Requests
                    </BLink>
                </li>
                <li class="nav-item" v-for="(list,index) in dropdowns.statuses" v-bind:key="index">
                    <BLink @click="viewStatus(index,list.value)" class="nav-link py-3" :class="(this.index == index) ? list.others+' active' : ''" data-bs-toggle="tab" role="tab" aria-selected="false">
                        <i :class="icons[index]" class="me-1 align-bottom"></i>
                        {{ list.name }} <BBadge v-if="counts[index] > 0" :class="list.color" class="align-middle ms-1">{{counts[index]}}</BBadge>
                    </BLink>
                </li>
            </ul>
        </div>
        <div class="flex-shrink-0">
            <div class="dropdown card-header-dropdown mt-3">
                <a class="text-reset dropdown-btn" href="#" target="_self" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="text-uppercase fw-semibold fs-11">Sort by : </span>
                    <span class="text-muted">{{filter.sortby}} ({{filter.sort}})<i class="mdi mdi-chevron-down ms-1"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" style="">
                    <a class="dropdown-item" href="#" @click="viewSort('Due At','asc')" target="_self">Due At (asc)</a>
                    <a class="dropdown-item" href="#" @click="viewSort('Due At','desc')" target="_self">Due At (desc)</a>
                    <a class="dropdown-item" href="#" @click="viewSort('Requested At','asc')" target="_self">Requested At (asc)</a>
                    <a class="dropdown-item" href="#" @click="viewSort('Requested At','desc')" target="_self">Requested At (desc)</a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th></th>
                    <th style="width: 30%;">Customer</th>
                    <th style="width: 7%;" class="text-center">Progress</th>
                    <th style="width: 10%;" class="text-center">Total</th>
                    <th style="width: 7%;" class="text-center">Payment</th>
                    <th style="width: 15%;" class="text-center">Date Request</th>
                    <th style="width: 13%;" class="text-center">Due Date</th>
                    <th style="width: 8%;" class="text-center">Status</th>
                    <th style="width: 7%;" ></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in lists" v-bind:key="index" @click="selectRow(index)" :class="{
                        'bg-dark-subtle': selectedRow === index && list.status.name !== 'Completed' && !isDueApproaching(list.due_at, list.status.name) && !isOverdue(list.due_at),
                        'bg-success-subtle': list.status.name === 'Completed',
                        'bg-warning-subtle': isDueApproaching(list.due_at, list.status.name),
                        'bg-danger-subtle': isOverdue(list.due_at)
                }">
                    <td class="text-center"> 
                        {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.
                    </td>
                    <td>
                        <h5 v-if="list.code" class="fs-13 mb-0 fw-semibold text-primary">{{list.code}}</h5>
                        <h5 v-else class="fs-13 mb-0 text-muted">Not yet available</h5>
                        <p class="fs-12 text-muted mb-0">{{list.customer.name}}</p>
                    </td>
                    <td>
                        <apexchart v-b-tooltip.hover :title="list.analyses+'%'" class="apex-charts" height="30" dir="ltr" :series="[list.analyses]" :options="{ ...chartOptions }"></apexchart>
                    </td>
                    <td class="text-center">{{list.payment.total}}</td>
                    <td class="text-center">
                        <i v-if="list.payment.is_paid" class="ri-checkbox-circle-fill text-success fs-18" v-b-tooltip.hover :title="list.payment.status.name"></i>
                        <i v-else-if="list.payment.is_free" class="ri-checkbox-circle-fill text-info fs-18" v-b-tooltip.hover title="Gratis"></i>
                        <i v-else-if="list.payment.status.name == 'Contract'" class="ri-information-fill text-warning fs-18" v-b-tooltip.hover title="Contract w/ MOA"></i>
                        <i v-else class="ri-close-circle-fill text-danger fs-18" v-b-tooltip.hover :title="list.payment.status.name"></i>
                    </td>
                    <td class="text-center fs-12">{{list.created_at}}</td>
                    <td class="text-center fs-12">
                        <span v-if="list.due_at">{{list.due_at}}</span>
                        <span class="text-muted" v-else>Not yet set</span>
                    </td>
                    <td class="text-center">
                        <span :class="'badge '+list.status.color">{{list.status.name}}</span>
                    </td>
                    <td class="text-end">
                        <a :href="`/tsrs/${list.qr}`" target="_blank">
                            <b-button variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                <i class="ri-eye-fill align-bottom"></i>
                            </b-button>
                        </a>
                        <b-button v-if="list.status.name !== 'Pending' && list.status.name !== 'Cancelled'" @click="openPrint(list.qr)" variant="soft-success" v-b-tooltip.hover title="Print" size="sm">
                            <i class="ri-printer-fill align-bottom"></i>
                        </b-button>
                        <b-button v-if="list.status.name == 'Cancelled'" variant="soft-danger" v-b-tooltip.hover title="Reason" size="sm">
                            <i class="ri-error-warning-fill align-bottom"></i>
                        </b-button>
                        <b-button v-if="list.status.name === 'Pending'" @click="openCancel(list,index)" variant="soft-danger" v-b-tooltip.hover title="Cancel" size="sm">
                            <i class="ri-delete-bin-2-fill align-bottom"></i>
                        </b-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Create :dropdowns="dropdowns" @success="moveTo" ref="create"/>
    <Cancel @success="fetch()" ref="cancel"/>
</template>
<script>
import _ from 'lodash';
import Create from '../Modals/Create.vue';
import Cancel from '../Modals/Cancel.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Create, Cancel },
    props: ['dropdowns','counts'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            index: null,
            icons: ['ri-information-line','ri-wallet-3-line','ri-indeterminate-circle-line','ri-checkbox-circle-line','ri-close-circle-line'],
            filter: {
                keyword: null,
                status: null,
                laboratory: null,
                sortby: 'Requested At',
                sort: 'desc',
                datetype: null,
                date:null
            },
            chartOptions: {
                chart: {
                type: "radialBar",
                    sparkline: {
                        enabled: true,
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: "30%",
                        },
                        track: {
                            margin: 1,
                        },
                        dataLabels: {
                            show: false,
                        },
                    },
                },
                colors: ["#099885"],
            },
            selectedRow: null,
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        },
        "filter.date"(newVal){
            this.fetch();
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/tsrs';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    status: this.filter.status,
                    sortby: this.filter.sortby,
                    sort: this.filter.sort,
                    date: this.filter.date,
                    datetype: this.filter.datetype,
                    laboratory: this.filter.laboratory,
                    count: Math.floor((window.innerHeight-390)/59),
                    option: 'lists'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;          
                }
            })
            .catch(err => console.log(err));
        },
        openCreate(){
            this.$refs.create.show();
        },
        openPrint(id){
            window.open('/tsrs?option=print&id='+id);
        },
        openCancel(data){
            this.$refs.cancel.show(data);
        },
        viewStatus(index,status){
            this.index = index;
            this.filter.status = status;
            this.fetch();
        },
        viewSort(sortby,sort){
            this.filter.sortby = sortby;
            this.filter.sort = sort;
            this.fetch();
        },
        moveTo(data){
            this.$inertia.visit('/tsrs/'+data);
        },
        refresh(){
            this.filter.sortby = 'Request At';
            this.filter.sort = 'desc';
            this.filter.keyword = null;
            this.filter.status = null;
            this.filter.laboratory = null;
            this.filter.date = null;
            this.filter.datetype = null;
            this.fetch();
        },
        selectRow(index) {
            this.selectedRow = index;
        },
        isDueApproaching(dueDate, status) {
            if (!dueDate || status !== 'Ongoing') return false; // Only check if status is 'Ongoing'
            const today = new Date();
            const due = new Date(dueDate);
            const diffTime = due - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays <= 5 && diffDays >= 0; // True if due date is within 5 days
        },
        isOverdue(dueDate) {
            if (!dueDate) return false; // If no due date, return false
            const today = new Date();
            const due = new Date(dueDate);
            return due <= today; // True if the due date is today or earlier (overdue)
        }
    }
}
</script>