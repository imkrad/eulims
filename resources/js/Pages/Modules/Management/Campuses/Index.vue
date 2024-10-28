<template>
    <PageHeader title="List of Campuses" pageTitle="List" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">

            <b-row class="g-2 mb-2 mt-n2">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="filter.keyword" placeholder="Search Campus" class="form-control" style="width: 100px;">
                        <Multiselect class="white" style="width: 10%;" :options="dropdowns.terms" v-model="filter.term" label="name" :searchable="true" placeholder="Select Term" />
                        <Multiselect class="white" style="width: 10%;" :options="dropdowns.gradings" v-model="filter.grading" label="name" :searchable="true" placeholder="Select Grading" />
                        <Multiselect class="white" style="width: 10%;" :options="dropdowns.agencies" v-model="filter.agency" label="name" :searchable="true" placeholder="Select Agency" />
                        <span @click="refresh()" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                            <i class="bx bx-refresh search-icon"></i>
                        </span>
                            <b-button @click="openCreate" type="button" variant="primary">
                                <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                        </b-button>
                    </div>
                </b-col>
            </b-row>
            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="fs-11">
                            <th style="width: 3%;"></th>
                            <th style="width: 35%;">Name</th>
                            <th style="width: 10%;" class="text-center">Abbreviation</th>
                            <th style="width: 10%;" class="text-center">Class</th>
                            <th style="width: 10%;" class="text-center">Grading</th>
                            <th style="width: 10%;" class="text-center">Term</th>
                            <th style="width: 10%;" class="text-center">Agency</th>
                            <th style="width: 5%;" class="text-center">Status</th>
                            <th style="width: 7%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="index">
                            <td> 
                                <div class="avatar-xs chat-user-img online">
                                    <span v-if="list.avatar == 'school.jpg'" :class="'avatar-title rounded-circle bg-primary text-white'">{{list.name[0]}}</span>
                                    <img v-else src="/images/logo-sm.png" alt="" class="avatar-xs rounded-circle">
                                    <span v-if="list.is_active" class="user-status text-success"></span>
                                </div>
                            </td>
                            <td>
                                <h5 class="fs-13 mb-0 text-dark">{{list.school.name}} {{(list.is_main) ? '' : '- '+list.campus}}</h5>
                                <p class="fs-12 text-muted mb-0">{{(list.address.address === '-') ? '' : list.address.address+','}} {{list.address.barangay.name}}, {{list.address.municipality.name}}, {{list.address.province.name}}, , {{list.address.region.region}}</p>
                            </td>
                            <td class="text-center">{{list.school.shortcut}}</td>
                            <td class="text-center">
                                {{list.school.class.name}} {{ (list.is_main) ? (list.school.class.name === 'SUC') ? ' - Main' : '' : ' - Satellite' }}
                            </td>
                            <td class="text-center">{{list.grading.name}}</td>
                            <td class="text-center">{{list.term.name}}</td>
                            <td class="text-center">{{list.agency.acronym}}</td>
                            <td class="text-center">
                                <span v-if="list.is_active" class="badge bg-success">Active</span>
                                <span v-else class="badge bg-danger">Inactive</span>
                            </td>
                            <td class="text-end">
                                <b-button @click="openEdit(list,index)" variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="me-1">
                                    <i class="ri-eye-fill align-bottom"></i>
                                </b-button>
                                <b-button @click="openEdit(list,index)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                                    <i class="ri-pencil-fill align-bottom"></i>
                                </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
        </div>
    </div>
</template>
<script>
import _ from 'lodash';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect },
    props:['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: { 
                keyword: null,
                term: null,
                grading: null,
                agency: null
            },
            index: null
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal);
        },
        "filter.term"(newVal){
            this.fetch();
        },
        "filter.grading"(newVal){
            this.fetch();
        },
        "filter.agency"(newVal){
            this.fetch();
        },
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/management';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    term: this.filter.term,
                    grading: this.filter.grading,
                    agency: this.filter.agency,
                    count: ((window.innerHeight-350)/59),
                    option: 'campuses'
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
            this.$inertia.visit('/management/school-create');
        },
        openUpdate(data,index){
            this.index = index;
            this.$refs.create.update(data);
        }
    }
}
</script>