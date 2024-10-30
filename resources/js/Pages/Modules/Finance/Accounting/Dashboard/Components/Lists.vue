<template>
    <b-row class="g-2 mb-2 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" v-model="filter.keyword" placeholder="Search Order of Payment" class="form-control" style="width: 55%;">
                <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="bx bx-refresh search-icon"></i>
                </span>
                <b-button v-if="$page.props.user.data.assigned_role == 'Accountant'" type="button" variant="primary" @click="openCreate">
                    <i class="ri-add-circle-fill align-bottom me-1"></i> Create
                </b-button>
            </div>
        </b-col>
    </b-row>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th></th>
                    <th style="width: 37%;">Customer</th>
                    <th style="width: 14%;" class="text-center">Collection</th>
                    <th style="width: 14%;" class="text-center">Payment</th>
                    <th style="width: 10%;" class="text-center">Status</th>
                    <th style="width: 12%;" class="text-center">Total</th>
                    <th style="width: 7%;" ></th>
                </tr>
            </thead>
            <tbody v-if="lists.length > 0">
                <tr v-for="(list,index) in lists" v-bind:key="index">
                    <td class="text-center"> 
                        {{ (meta.current_page - 1) * meta.per_page + index + 1 }}.
                    </td>
                    <td>
                        <h5 class="fs-13 mb-0 text-dark" v-if="list.or">OR# : {{list.or.number}}</h5>
                        <h5 class="fs-13 mb-0 text-dark" v-else>{{list.code}}</h5>
                        <p class="fs-12 text-muted mb-0">{{list.payorable.name}}</p>
                    </td>
                    <td class="text-center fs-12">{{list.collection}}</td>
                    <td class="text-center fs-12">{{list.payment.name}}</td>
                    <td class="text-center">
                        <span :class="'badge '+list.status.color+' '+list.status.others">{{list.status.name}}</span>
                    </td>
                    <td class="text-center">{{list.total}}</td>
                    <td v-if="$page.props.user.data.assigned_role == 'Accountant'" class="text-end">
                        <b-button @click="openView(list)" variant="soft-info" class="me-1" v-b-tooltip.hover title="View" size="sm">
                            <i class="ri-eye-fill align-bottom"></i>
                        </b-button>
                        <b-button @click="openPrint(list.id)" variant="soft-success" class="me-1" v-b-tooltip.hover title="Print" size="sm">
                            <i class="ri-printer-fill align-bottom"></i>
                        </b-button>
                        <b-button @click="openDelete(list)" v-if="$page.props.user.data.assigned_role == 'Accountant' && list.status.name == 'Pending'" variant="soft-danger" class="me-1" v-b-tooltip.hover title="Delete" size="sm">
                            <i class="ri-delete-bin-fill align-bottom"></i>
                        </b-button>
                    </td>
                    <td v-else class="text-end">
                        <b-button type="button" variant="primary" size="sm" @click="openView(list)">
                            Mark as paid
                        </b-button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        There are no records to display
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
    <Create :collections="collections" :payments="payments" @update="fetch()" ref="create"/>
    <View @update="fetch()" ref="view"/>
    <Delete @update="fetch()" ref="delete"/>
</template>
<script>
import _ from 'lodash';
import View from '../Modals/View.vue';
import Create from '../Modals/Create.vue';
import Delete from '../Modals/Delete.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination, Create, View, Delete },
    props: ['collections','payments','deposits','orseries','statuses'],
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            index: null,
            filter: {
                keyword: null,
                status: null
            }
        }
    },
    created(){
        this.fetch();
    },
    watch: {
        "filter.keyword"(newVal){
            this.checkSearchStr(newVal)
        }
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url){
            page_url = page_url || '/orderofpayments';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    status: 6,
                    count: ((window.innerHeight-490)/58),
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
        openView(data){
            this.$refs.view.show(data);
        },
        openDelete(data){
            this.$refs.delete.show(data);
        },
        openPrint(id){
            window.open(this.currentUrl + '/orderofpayments?option=print&id='+id);
        },
    }
}
</script>