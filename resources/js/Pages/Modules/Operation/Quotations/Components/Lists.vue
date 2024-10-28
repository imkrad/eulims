<template>
    <b-row class="g-2 mb-2 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="filter.keyword" placeholder="Search Request" class="form-control" style="width: 55%;">
                <select v-model="filter.status" @change="fetch()" class="form-select" id="inputGroupSelect01">
                    <option :value="null" selected>Select Status</option>
                    <option :value="list.value" v-for="list in dropdowns.statuses" v-bind:key="list.id">{{list.name}}</option>
                </select>
                <select v-model="filter.laboratory" @change="fetch()" class="form-select" id="inputGroupSelect01" style="width: 140px;">
                    <option :value="null" selected>Select Laboratory</option>
                    <option :value="list.value" v-for="list in dropdowns.laboratories" v-bind:key="list.id">{{list.name}}</option>
                </select>
                <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="bx bx-refresh search-icon"></i>
                </span>
                <b-button type="button" variant="primary" @click="openCreate">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                </b-button>
            </div>
        </b-col>
    </b-row>
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
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th></th>
                    <th style="width: 25%;">Customer</th>
                    <th style="width: 15%;" class="text-center">Laboratory</th>
                    <th style="width: 15%;" class="text-center">Created By</th>
                    <th style="width: 15%;" class="text-center">Created At</th>
                    <th style="width: 10%;" class="text-center">Status</th>
                    <th style="width: 10%;" class="text-center">Total</th>
                    <th style="width: 7%;" ></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(list,index) in lists" v-bind:key="index">
                    <td class="text-center"> 
                        {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.
                    </td>
                    <td>
                        <h5 v-if="list.code" class="fs-13 mb-0 fw-semibold text-primary">{{list.code}}</h5>
                        <h5 v-else class="fs-13 mb-0 text-muted">Not yet available</h5>
                        <p class="fs-12 text-muted mb-0">{{list.customer.name}}</p>
                    </td>
                    <td class="text-center">{{list.type.name}}</td>
                    <td class="text-center">{{list.received}}</td>
                    <td class="text-center">{{list.created_at}}</td>
                     <td class="text-center">
                        <span :class="'badge '+list.status.color">{{list.status.name}}</span>
                    </td>
                    <td class="text-center fs-12">{{list.total}}</td>
                    <td class="text-end">
                        <Link :href="`/quotations/${list.qr}`">
                            <b-button variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                                <i class="ri-eye-fill align-bottom"></i>
                            </b-button>
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Create :dropdowns="dropdowns" @success="fetch()" ref="create"/>
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
                laboratory: null
            }
        }
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
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
            page_url = page_url || '/quotations';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    status: this.filter.status,
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
            window.open('/requests?option=print&id='+id);
        },
        openCancel(data){
            this.$refs.cancel.show(data);
        },
        viewStatus(index,status){
            this.index = index;
            this.filter.status = status;
            this.fetch();
        }
    }
}
</script>