<template>
  <div id="app">
    <div class="heading">
      <h1>Cruds</h1>
    </div>
    <crud-component
      v-for="crud in cruds"
      v-bind="crud"
      :key="crud.id"
      @update="update"
      @delete="del"
    ></crud-component>
    <div>
      <button @click="create">Add</button>
    </div>
  </div>
</template>

<script>
  function Crud({ id, color, name}) {
    this.id = id;
    this.color = color;
    this.name = name;
  }

  import CrudComponent from './components/CrudComponent.vue';

  export default {
    data() {
      return {
        cruds: [],
        mute: false
      }
    },
    methods: {
      async create() {
        const { data } = window.axios.get('/api/v1/test1');
        this.cruds.push(new Crud(data));
      },
      async read() {
        const { data } = window.axios.get('/api/v1/test2');
        data.forEach(crud => this.cruds.push(new Crud(crud)));
        // console.log(data)
      },
      async update(id, color) {
        this.mute = true;
        await window.axios.put(`/api/cruds/${id}`, { color });
        // Once AJAX resolves we can update the Crud with the new color
        this.cruds.find(crud => crud.id === id).color = color;
        this.mute = false;
      },
      async del(id) {
        await window.axios.delete(`/api/cruds/${id}`);
        let index = this.cruds.findIndex(crud => crud.id === id);
        this.cruds.splice(index, 1);
      }
    },
    created() {
      this.read();
    },
    components: {
      CrudComponent
    }
  }
</script>