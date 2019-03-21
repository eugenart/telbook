<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Группы</h4>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="modalForNewGroup">Добавить
                                группу
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <div class="col-12">
                            <div class="input-group mt-3 mb-3">
                                <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-search"></i></span>
                                </div>
                                <input @input="searchIt" autofocus @keyup="searchIt" v-model="search"
                                       class="col-12 custom-search form-control"
                                       type="search"
                                       placeholder="Поиск группы" aria-label="Search">
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th width="25%" scope="col">Родительская группа</th>
                                <th width="25%" scope="col">Название группы</th>
                                <th width="10%" scope="col">Email</th>
                                <th width="15%" scope="col">Телефон</th>
                                <th width="25%" scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <!-- //'name', 'parent_id', 'priority', 'email', 'phone'-->
                            <tr v-for="group in groups">
                                <td>
                                    <span v-for="sub_group in groups" v-if="group.parent_id == sub_group.id">
                                        <span class="badge badge-warning">{{ sub_group.name }}</span>
                                    </span>
                                    <span v-if="!group.parent_id">
                                        <span class="badge badge-primary">Группа верхнего уровня</span>
                                    </span>
                                </td>
                                <td>{{ group.name }}</td>
                                <td>
                                    <p class="mb-0" v-for="mail in group.email">
                                        {{ mail }}
                                    </p>
                                </td>
                                <td>
                                    <p class="mb-0" v-for="phone in group.phone">
                                        {{ cityTypes[phone.phonePrefix-1] }}
                                        {{ phone.phoneNumber }}<br>
                                    </p>

                                </td>
                                <td>
                                    <button class="btn btn-warning" @click="modalForChangeGroup(group)">
                                        Редактировать
                                    </button>
                                    <button @click="deleteGroup(group.id, group.name)"
                                            class="btn btn-danger">Удалить
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--<div class="card-footer">-->
                    <!--<div class="col-12">-->
                    <!--<nav aria-label="Page navigation example">-->
                    <!--<ul class="pagination justify-content-center">-->
                    <!--<li class="page-item"><a class="page-link" href="#"-->
                    <!--v-on:click="loadPaginateGroups(pagination.first_page_url)"-->
                    <!--:disabled="!pagination.first_page_url">Первая</a>-->
                    <!--</li>-->
                    <!--<li class="page-item"><a class="page-link" href="#"-->
                    <!--v-on:click="loadPaginateGroups(pagination.prev_page_url)"-->
                    <!--:disabled="!pagination.prev_page_url"><<</a>-->
                    <!--</li>-->
                    <!--<li class="page-item"><a class="page-link" href="#">{{ pagination.current_page-->
                    <!--}}</a></li>-->
                    <!--<li class="page-item"><a class="page-link" href="#"-->
                    <!--v-on:click="loadPaginateGroups(pagination.next_page_url)"-->
                    <!--:disabled="!pagination.next_page_url">>></a>-->
                    <!--</li>-->
                    <!--<li class="page-item"><a class="page-link" href="#"-->
                    <!--v-on:click="loadPaginateGroups(pagination.last_page_url)"-->
                    <!--:disabled="!pagination.last_page_url">Последняя</a>-->
                    <!--</li>-->
                    <!--</ul>-->
                    <!--</nav>-->
                    <!--</div>-->
                    <!--</div>-->
                </div>
            </div>
        </div>

        <div class="modal fade" id="GroupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавление группы</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editGroup ? updateGroup() : createGroup()">
                        <div class="modal-body">
                            <div class="input-group mb-3">
                                <model-list-select :list="newGroups" option-value="id" option-text="name"
                                                   v-model="form.parent_id" placeholder="Выберите родительский элемент">
                                </model-list-select>
                            </div>
                            <div class="form-group">
                                <input placeholder="Название группы" v-model="form.name" type="text" name="name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-row" v-for="(mail,index) in form.email">
                                <div class="form-group col-md-8">
                                    <input placeholder="Email" v-model="form.email[index]" type="text" name="email[]"
                                           class="form-control"
                                           :class="{ 'is-invalid': form.errors.has('form.email[index]') }">
                                    <has-error :form="form" field="form.email[index]"></has-error>
                                </div>
                                <div class="form-group col-md-4">
                                    <button @click="deleteMail(index)" type="button"
                                            class="btn btn-danger float-right">Удалить почту
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" @click="addMail">Добавить почту</button>
                            </div>
                            <div class="form-row" v-for="(phn,index) in form.phone">
                                <div class="form-group col-md-4">
                                    <select class="custom-select" v-model="form.phone[index].phonePrefix">
                                        <option value="1">Саранск +7(8342)</option>
                                        <option value="2">Рузаевка +7(83451)</option>
                                        <option value="3">Ковылкино +7(83453)</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <masked-input :mask="form.phone[index].phonePrefix == 1 ? '11-11-11' : '1-11-11'"
                                                  placeholder="Номер телефона" v-model="form.phone[index].phoneNumber"
                                                  type="text"
                                                  class="form-control"
                                                  :class="{ 'is-invalid': form.errors.has('form.phone[index].phoneNumber') }"/>
                                    <has-error :form="form" field="form.phone[index].phoneNumber"></has-error>
                                </div>
                                <div class="form-group col-md-4">
                                    <button @click.prevent="deletePhone(index)" type="button"
                                            class="btn btn-danger float-right">Удалить номер
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" @click.prevent="addPhone()">Добавить номер
                                </button>
                            </div>
                            <div class="input-group mb-3">
                                <select class="custom-select" id="inputGroupSelect02"
                                        v-model="form.priority">
                                    <option value="1">Очень высокий</option>
                                    <option value="2">Высокий</option>
                                    <option value="3">Средний</option>
                                    <option value="4">Низкий</option>
                                    <option value="5">Очень низкий</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button v-show="!editGroup" type="submit" class="btn btn-success">Добавить группу
                            </button>
                            <button v-show="editGroup" type="submit" class="btn btn-primary">Сохранить изменения
                            </button>
                            <button type="button" class="btn btn-danger" @click.prevent="resetForm()"
                                    data-dismiss="modal">Закрыть
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MaskedInput from "vue-masked-input";

    export default {
        components: {MaskedInput, ModelSelect, ModelListSelect},
        data() {
            return {
                cityTypes: [
                    '+7(8342)',
                    '+7(83451)',
                    '+7(83453)'
                ],
                search: '',
                editGroup: true,
                groups: {},
                url: '/listgroup',
                pagination: [],
                newGroups: [],
                form: new Form({ //'name', 'parent_id', 'priority', 'email', 'phone'
                    id: '',
                    name: '',
                    parent_id: '',
                    priority: '3',
                    email: [],
                    phone: []
                })
            }
        },


        methods: {
            searchIt: _.debounce(() => {
                Fire.$emit('searching');
            }, 300),

            addMail() {
                this.form.email.push('');
                this.form.clear();
            },

            deleteMail(index) {
                this.form.email.splice(index, 1);
            },

            addPhone() {
                this.form.phone.push({
                    phonePrefix: '1',
                    phoneNumber: ''
                });
                this.form.clear();
            },

            deletePhone(index) {
                this.form.phone.splice(index, 1);
            },

            updateGroup(id) {
                this.$Progress.start();
                this.form.put('/api/group/' + this.form.id)
                    .then(() => {
                        toast({
                            type: 'success',
                            title: 'Группа обновлена'
                        });
                        $('#GroupModal').modal('hide');
                        Fire.$emit('UpdateGroupTable');
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },

            createGroup() {
                this.$Progress.start()
                this.form.post('/api/group')
                    .then(() => {
                        Fire.$emit('UpdateGroupTable');
                        $('#GroupModal').modal('hide');
                        toast({
                            type: 'success',
                            title: 'Группа добавлена'
                        })
                        this.$Progress.finish();
                    })
                    .catch(() => {

                    })
            },

            loadPaginateGroups(url) {
                console.log(url)
                this.url = url;
                this.loadGroups();
            },

            loadGroups() {
                axios.get(this.url)
                    .then(
                        ({data}) => (this.groups = data,
                            console.log(data))
                        //this.makePagination(data))
                    );
            },

            // makePagination(data) {
            //     let pagination = {
            //         current_page: data.current_page,
            //         last_page: data.last_page,
            //         last_page_url: data.last_page_url,
            //         first_page_url: data.first_page_url,
            //         next_page_url: data.next_page_url,
            //         prev_page_url: data.prev_page_url,
            //         total: data.total
            //
            //     }
            //     this.pagination = pagination
            // },

            loadNewGroups() {
                axios.get('/listgroup?q=parents')
                    .then(
                        ({data}) => (this.newGroups = data),
                        this.newGroups.unshift({
                            'id': ' ',
                            'name': 'Нет родительской группы'
                        }))
            },

            modalForNewGroup() {
                {
                    this.editGroup = false;
                    this.form.reset();
                    $('#GroupModal').modal('show');
                }
            },

            modalForChangeGroup(group) {
                let prev_group = JSON.parse(JSON.stringify(group));
                this.editGroup = true;
                this.form.reset();
                $('#GroupModal').modal('show');
                this.form.fill(prev_group);
                if (this.form.phone == []) {
                    this.form.phone = [];
                }
                ;
                if (this.form.email == []) {
                    this.form.email = [];
                }
            },

            deleteGroup(id, name) {
                swal({
                    title: 'Вы уверены?',
                    text: "После удаления будет невозможно восстановить данный элемент",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Удалить',
                    cancelButtonText: 'Отмена'
                }).then((result) => {

                        if (result.value) {
                            this.form.delete('/api/group/' + id).then(() => {
                                swal(
                                    'Группа ' + name + ' была удалена'
                                )
                                Fire.$emit('UpdateGroupTable');

                            }).catch(() => {
                                swal(
                                    'Группа ' + name + ' не была удалена', 'warning'
                                )

                            })
                        }
                    }
                )
            },

            resetForm() {
                this.form.reset();
            },

            seekChild() {
                console.log('wooow, children')
            }

        }
        ,

        created() {
            Fire.$on('searching', () => {
                let query = this.search;
                if (query.length) {
                    axios.get('/listgroup/search?q=' + query)
                        .then((data) => {
                                this.groups = data.data,
                                    console.log(data);
                            }
                        )
                } else if (this.search.length < 1) {
                    //this.pagination.current_page = 1;
                    this.loadGroups();
                }
            });
            this.loadNewGroups();
            this.loadGroups();
            Fire.$on('UpdateGroupTable', () => {
                this.loadGroups();
                this.loadNewGroups();
            });
        }
    }
</script>

<style scoped>

</style>
