<template xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Сотрудники ({{ pagination.total }})</h4>
                        <div class="card-tools">
                            <button @click="modalForNewMan" data-toggle="modal" data-target="#PeopleEditModal"
                                    class="btn btn-success">Добавить
                                сотрудника
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group  ">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i
                                            class="fas fa-search"></i></span>
                                    </div>
                                    <input @input="searchIt" autofocus @keyup="searchIt" v-model="search"
                                           class="col-12 custom-search form-control"
                                           type="search"
                                           placeholder="Поиск сотрудников" aria-label="Search">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="list-group" id="list-tab" role="tablist">
                                    <a @click="this.isActive = !this.isActive" v-for="man in people"
                                       :class="{active: isActive}" class="list-group-item list-group-item-action"
                                       :id="'list-user-' + man.id"
                                       data-toggle="list" :href="'#user-'+man.id" role="tab" aria-controls="home">{{
                                        man.fio }}</a>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="tab-content" id="nav-tabContent">
                                    <div v-for="man in people" class="tab-pane fade show" :id="'user-'+man.id"
                                         role="tabpanel"
                                         aria-labelledby="list-home-list">
                                        <p><b>ФИО: </b> {{ man.fio }}</p>
                                        <p v-for="group in groups" v-if='man.group_id && group.id == man.group_id'><b>Группа: </b>
                                            {{group.name }}</p>
                                        <p><b>Должность: </b>{{ man.position }}</p>
                                        <p><b>Внешние номера: <br></b>
                                            <span v-for="phone in man.phone" v-if="man.phone">
                                        {{ cityTypes[phone.phonePrefix-1] }}
                                        {{ phone.phoneNumber}}
                                            <br></span></p>
                                        <p><b>Внутренние номера: <br></b><span v-for="ip in man.ip_phone"
                                                                               v-if="man.ip !== []">
                                            {{ ip }}<br></span></p>
                                        <p><b>Почта: <br></b><span v-for="mail in man.email" v-if="man.email !== []">
                                            {{ mail }} <br></span></p>
                                        <p v-for="building in buildings"
                                           v-if="man.building_id && building.id == man.building_id"><b>Адрес: </b>{{
                                            building.name }}</p>
                                        <p><b>Место: </b>{{ man.room }} {{ roomTypes[man.room_type -1] }}</p>

                                        <p>
                                            <button class="btn btn-warning" @click="modalForChangeMan(man)">
                                                Редактировать
                                            </button>
                                            <button class="btn btn-danger" @click="deleteMan(man.id, man.fio)">Удалить
                                            </button>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="col-12">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item"><a class="page-link" href="#"
                                                             v-on:click="loadPaginatePeople(pagination.first_page_url)"
                                                             :disabled="!pagination.first_page_url">Первая</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#"
                                                             v-on:click="loadPaginatePeople(pagination.prev_page_url)"
                                                             :disabled="!pagination.prev_page_url"><<</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">{{ pagination.current_page
                                        }}</a></li>
                                    <li class="page-item"><a class="page-link" href="#"
                                                             v-on:click="loadPaginatePeople(pagination.next_page_url)"
                                                             :disabled="!pagination.next_page_url">>></a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#"
                                                             v-on:click="loadPaginatePeople(pagination.last_page_url)"
                                                             :disabled="!pagination.last_page_url">Последняя</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="PeopleEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить сотрудника</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editPeople ? updateMan() : createMan()">
                        <div class="modal-body">
                            <div class="under-div  ">
                                <div class="form-group">
                                    <input type="text" v-model="form.fio" placeholder="ФИО" class="form-control"
                                           required>
                                </div>
                                <div class="form-group">
                                    <model-list-select :list="groups" option-value="id" option-text="name" required
                                                       v-model="form.group_id"
                                                       placeholder="Выберите институт/факультет/подразделение">
                                    </model-list-select>
                                </div>
                                <div class="form-group">
                                    <input required v-model="form.position" type="text" placeholder="Должность"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <select class="form-control" v-model="form.status">
                                        <option value="1">Очень высокий приоритет</option>
                                        <option value="2">Высокий приоритет</option>
                                        <option value="3">Средний приоритет</option>
                                        <option value="4">Низкий приоритет</option>
                                        <option value="5">Очень низкий приоритет</option>
                                    </select>
                                </div>
                            </div>
                            <div class="under-div  ">

                                <div class="form-row" v-for="(mail,index) in form.email">
                                    <div class="form-group col-md-8">
                                        <input placeholder="Email" v-model="form.email[index]" type="text"
                                               name="email[]"
                                               class="form-control col-8 float-left mr-1"
                                               :class="{ 'is-invalid': form.errors.has('form.email[index]') }">
                                        <has-error :form="form" field="form.email[index]"></has-error>
                                        <button @click="deleteMail(index)" type="button"
                                                class="form-control btn btn-danger float-left col-1">&#10006;
                                        </button>
                                    </div>
                                    <div class="form-group col-md-4">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-4 m-0">
                                        <button @click="addMail()" class="btn btn-primary col-12" type="button">
                                            Добавить
                                            Email
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="under-div  ">
                                <div class="form-row" v-for="(phn,index) in form.phone">
                                    <div class="form-group col-md-4">
                                        <select class="custom-select" v-model="form.phone[index].phonePrefix">
                                            <option value="1">Саранск +7(8342)</option>
                                            <option value="2">Рузаевка +7(83451)</option>
                                            <option value="3">Ковылкино +7(83453)</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <masked-input
                                            :mask="form.phone[index].phonePrefix == 1 ? '11-11-11' : '1-11-11'"
                                            placeholder="Номер телефона" v-model="form.phone[index].phoneNumber"
                                            type="text"
                                            class="form-control float-left col-8 mr-1"
                                            :class="{ 'is-invalid': form.errors.has('form.phone[index].phoneNumber') }"/>
                                        <has-error :form="form" field="form.phone[index].phoneNumber"></has-error>
                                        <button @click.prevent="deletePhone(index)" type="button"
                                                class="form-control btn btn-danger float-left col-2">&#10006;
                                        </button>
                                    </div>
                                    <div class="form-group col-md-4">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-4 m-0">
                                        <button type="button" class="btn btn-primary col-12"
                                                @click.prevent="addPhone()">
                                            Добавить внешний номер
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="under-div  ">
                                <div class="form-row" v-for="(ipPhone,index) in form.ip_phone">
                                    <div class="form-group col-md-4">
                                        <masked-input
                                            :mask="'1111'"
                                            maxlength="4" placeholder="Внутренний номер" v-model="form.ip_phone[index]"
                                            type="tel"
                                            class="form-control col-8 float-left mr-1"
                                            :class="{ 'is-invalid': form.errors.has('form.ip_phone[index]') }"/>
                                        <has-error :form="form" field="form.ip_phone[index]"></has-error>
                                        <button @click="deleteIpPhone(index)" type="button"
                                                class="btn btn-danger float-left col-2">&#10006;
                                        </button>
                                    </div>
                                    <div class="form-group col-md-4">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-4 m-0">
                                        <button @click="addIpPhone()" class="btn btn-primary col-12" type="button">
                                            Добавить
                                            внутренний номер
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="under-div ">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <select v-model="selectedItem" @change="loadNewBuildings(selectedItem)"  required class="form-control">
                                            <option value="-1">Выберите тип здания</option>
                                            <option v-for="bid in build_groups" :value="bid.id">{{bid.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select v-if="selectedItem != -1" required v-model="form.building_id" class="form-control">
                                            <option value="-1">Выберите зданиe</option>
                                            <option v-for="building in buildings" :value="building.id">{{building.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input v-if="form.building_id" required v-model="form.room" type="text"
                                               placeholder="Номер кабинета/этажа"
                                               class="form-control">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select v-if="form.building_id" required v-model="form.room_type" class="form-control">
                                            <option value="">Кабинет/этаж</option>
                                            <option value="1">Кабинет</option>
                                            <option value="2">Этаж</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button v-show="!editPeople" type="submit" class="btn btn-success">Добавить сотрудника
                            </button>
                            <button v-show="editPeople" type="submit" class="btn btn-primary">Сохранить изменения
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {MaskedInput, ModelSelect, ModelListSelect, VueGoodTable},
        data() {
            return {
                roomTypes: [
                    'кабинет',
                    'этаж'
                ],
                cityTypes: [
                    '+7(8342)',
                    '+7(83451)',
                    '+7(83453)'
                ],
                isActive: false,
                people: [],
                groups: [],
                buildings: {},
                build_groups: [],
                newGroups: [],
                editPeople: false,
                search: '',
                selectedItem: '',
                pagination: [],
                url: '/liststaff',
                form: new Form({ //'fio', 'position', 'phone', 'ip_phone', 'building_id', 'group_id', 'room', 'room_type', 'email'
                    id: '',
                    fio: '',
                    position: '',
                    status: '3',
                    phone: [],
                    fax: [],
                    ip_phone: [],
                    building_id: '',
                    group_id: '',
                    room: '',
                    room_type: '',
                    email: []
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

            addIpPhone() {
                this.form.ip_phone.push('');
                this.form.clear();
            },

            deleteIpPhone(index) {
                this.form.ip_phone.splice(index, 1);
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

            addFax() {
                this.form.fax.push({
                    phonePrefix: '1',
                    phoneNumber: ''
                });
                this.form.clear();
            },

            deleteFax(index) {
                this.form.fax.splice(index, 1);
            },

            loadPaginatePeople(url) {
                this.url = url;
                this.loadPeople();
            },

            loadPeople() {
                axios.get(this.url)
                    .then(
                        ({data}) => (this.people = data.data,
                                this.makePagination(data)
                        )
                    );
            },

            makePagination(data) {
                let pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    last_page_url: data.last_page_url,
                    first_page_url: data.first_page_url,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                    total: data.total

                }
                this.pagination = pagination
            },

            loadGroups() {
                axios.get('/listgroup?q=children')
                    .then(
                        ({data}) => (this.groups = data))
            },

            loadBuildings() {
                axios.get('/api/get_all_buildings')
                    .then(
                        ({data}) => (this.buildings = data)
                    )
            },

            loadNewBuildings(id) {
                console.log(id)
                axios.get('/api/get_choosen_builds/' + id)
                    .then(
                        ({data}) => (this.buildings = data)
                    )
                this.form.building_id = '-1'
            },

            loadgrids() {
                axios.get('/api/get_all_group_ids')
                    .then(
                        ({data}) => (this.build_groups = data, console.log(data))
                    )
            },


            modalForNewMan() {
                {
                    this.editPeople = false;
                    this.form.reset();
                    $('#PeopleEditModal').modal('show');
                    this.selectedItem = '-1';
                }
            },

            modalForChangeMan(man) {
                let prev_group = JSON.parse(JSON.stringify(man));
                this.editPeople = true;
                this.form.reset();
                $('#PeopleEditModal').modal('show');
                this.form.fill(prev_group);
                this.selectedItem = man.type_id;
                if (this.form.phone == []) {
                    this.form.phone = [];
                }
                if (this.form.email == []) {
                    this.form.email = [];
                }
                if (this.form.ip_phone == []) {
                    this.form.ip_phone = [];
                }
            },

            createMan() {
                this.$Progress.start()
                this.form.post('/api/people')
                    .then(() => {
                        Fire.$emit('UpdatePeopleTable');
                        $('#PeopleEditModal').modal('hide');
                        toast({
                            type: 'success',
                            title: 'Пользователь добавлен'
                        })
                        this.$Progress.finish();
                    })
                    .catch(() => {

                    })
            },

            deleteMan(id, name) {
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
                            this.form.delete('/api/people/' + id).then(() => {
                                swal(
                                    'Пользователь ' + name + ' был удален'
                                )
                                Fire.$emit('UpdatePeopleTable');

                            }).catch(() => {
                                swal(
                                    'Пользователь ' + name + ' не был удален'
                                )

                            })
                        }
                    }
                )
            },

            updateMan(id) {
                this.$Progress.start();
                this.form.put('/api/people/' + this.form.id)
                    .then(() => {
                        toast({
                            type: 'success',
                            title: 'Пользователь обновлен'
                        });
                        $('#PeopleEditModal').modal('hide');
                        Fire.$emit('UpdatePeopleTable');
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            },
        },
        created() {
            Fire.$on('searching', () => {
                let query = this.search;
                if (query.length) {
                    axios.get('/liststaff/search?q=' + query)
                        .then((data) => {
                                this.people = data.data.data
                            }
                        )
                } else if (this.search.length < 1) {
                    this.pagination.current_page = 1;
                    this.loadPeople();
                }
            });
            this.loadBuildings();
            this.loadGroups();
            this.loadPeople();
            this.loadgrids();
            Fire.$on('UpdatePeopleTable', () => {
                this.loadBuildings();
                this.loadGroups();
                this.loadPeople();
                this.loadgrids();
            });
        }
    }
</script>

<style scoped>
    .modal-dialog {
        overflow-y: scroll !important;
    }

    .modal-open .modal {
        overflow-y: scroll !important;
    }

    .modal-content {
        overflow-y: scroll !important;
    }

    .under-div {
        background-color: #fff;
        padding: 1rem;
        border-bottom: 1px solid #ced4da;
    }

    .modal-content {
        background-color: transparent;
    }

    .modal-body {
        background-color: transparent;
        padding: 0;
    }

    .modal-header {
        background-color: #fff;
        padding: 1rem;
    }

    .modal-footer {
        background-color: #fff;
        padding: 1rem;
    }

    .custom-search {
        outline: none !important;
        box-shadow: none;
    }
</style>
