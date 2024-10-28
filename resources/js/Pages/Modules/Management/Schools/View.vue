<template>
    <!-- <PageHeader title="View School" pageTitle="List" /> -->
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="w-100 p-4 pb-0" ref="box">

            <BRow>
                <BCol lg="12">
                    <BCard no-body class="mt-n4 mx-n4">
                        <div class="bg-warning-subtle">
                            <BCardBody class="pb-0 px-4">
                                <BRow class="mb-3">
                                    <BCol md>
                                        <BRow class="align-items-center g-3">
                                            <BCol md="auto">
                                                <div class="avatar-md">
                                                    <div class="avatar-title bg-white rounded-circle">
                                                    </div>
                                                </div>
                                            </BCol>
                                            <BCol md>
                                                <div>
                                                    <h4 class="fw-bold">{{ school.name }}</h4>
                                                    <div class="hstack gap-3 flex-wrap">
                                                        <div><i class="ri-building-line align-bottom me-1"></i> {{school.shortcut}}
                                                        </div><div class="vr"></div>
                                                        <div>Class : <span class="fw-medium">{{school.class.name}}</span></div>
                                                        <div class="vr"></div>
                                                        <div>Create Date : <span class="fw-medium">{{school.created_at}}</span></div>
                                                        <div class="vr"></div> 
                                                        <div>Campuses : <span class="fw-medium">{{school.campuses_count}}</span></div>
                                                        <div class="vr"></div>
                                                    </div>
                                                </div>
                                            </BCol>
                                        </BRow>
                                    </BCol>
                                    <BCol md="auto">
                                        <div class="hstack gap-1 flex-wrap">
                                            <button @click="back()" type="button" class="btn py-0 fs-16 text-body">
                                                <i class="ri-share-line"></i>
                                            </button>
                                        </div>
                                    </BCol>
                                </BRow>
                            </BCardBody>
                        </div>
                    </BCard>
                </BCol>
                <BCol lg="8">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">{{school.campuses.length}} Campuses</h4>
                            <div class="flex-shrink-0">
                                <button @click="openCreate()" class="btn btn-soft-primary btn-sm" type="button">
                                    <div class="btn-content"> Add Campus </div>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="height: calc(100vh - 350px);">
                            <div class="table-responsive">
                                <simplebar data-simplebar style="height: calc(100vh - 390px);">
                                    <table class="table table-nowrap align-middle mb-0">
                                        <thead class="table-light thead-fixed">
                                            <tr class="fs-11">
                                                <th style="width: 4%;" class="text-center">#</th>
                                                <th style="width: 33%;">Name</th>
                                                <th style="width: 10%;" class="text-center">Grading</th>
                                                <th style="width: 10%;" class="text-center">Term</th>
                                                <th style="width: 10%;" class="text-center">Agency</th>
                                                <th style="width: 5%;" class="text-center">Status</th>
                                                <th style="width: 7%;"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="fs-12" v-for="(list,index) in school.campuses" v-bind:key="index" :class="{'bg-success-subtle': list.is_main}">
                                                <td class="text-center">{{ index+1 }}</td>
                                                <td>
                                                    <h5 class="fs-12 mb-0 text-dark">{{(list.is_main) ? 'Main Campus' : list.campus }}</h5>
                                                    <p class="fs-11 text-muted mb-0">{{(list.address.address === '-') ? '' : list.address.address+','}} {{list.address.barangay.name}}, {{list.address.municipality.name}}, {{list.address.province.name}}, , {{list.address.region.region}}</p>
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
                                                    <b-button @click="openEdit(list)" variant="soft-warning" v-b-tooltip.hover title="Edit" size="sm">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </b-button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </simplebar>
                            </div>

                        </div>
                    </div>
                </BCol>
                <BCol lg="4">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Informations</h4>
                            <div class="flex-shrink-0">
                                <button @click="openName()" class="btn btn-soft-primary btn-sm" type="button">
                                    <div class="btn-content"> Add Name</div>
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="height: calc(100vh - 350px); overflow: auto;">
                            <p class="text-muted text-uppercase fs-12 fw-medium mb-2">Reminders</p>
                            <b-list-group>
                                <BListGroupItem @click="filterReminder(list.name)" v-for="(list,index) in notes" v-bind:key="index">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xs">
                                                <div class="avatar-title rounded" :class="list.color">
                                                <i class="fs-15" :class="list.icon"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-0 fs-12">{{list.name}}</h5>
                                            <p class="mb-0 fs-11 text-muted">{{list.description}}</p>
                                        </div>
                                        <span class="text-muted fs-12">{{list.count}} </span>
                                    </div>
                                </BListGroupItem>
                            </b-list-group>
                            <!-- <p class="text-muted text-uppercase fs-12 fw-medium mb-2 mt-3">Name History</p>
                            <b-list-group>
                                <b-list-group-item><i class="mdi mdi-check-bold align-middle lh-1 me-2"></i> Testing 123</b-list-group-item>
                            </b-list-group> -->
                        </div>
                    </div>
                </BCol>
            </BRow>
            
        </div>
    </div>
    <Campus :dropdowns="dropdowns" ref="campus"/>
</template>
<script>
import _ from 'lodash';
import Campus from './Modals/Campus.vue';
import simplebar from "simplebar-vue";
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect, simplebar, Campus },
    props:['school','notes','dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: { 
                keyword: null,
                class: null
            },
            index: null,
        }
    },
    methods: {
        openCreate(){
            this.$refs.campus.show(this.school.id);
        },
        openEdit(data){
            this.$refs.campus.edit(data);
        },
        back(){
            this.$inertia.visit('/management/schools');
        }
    }
}
</script>