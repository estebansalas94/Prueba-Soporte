
    <template>
        <div class="container mt-5">
            <h1 class="text-center mb-4">Task List</h1>
    
            <!-- Botones para mostrar todas las tareas o solo las tareas asignadas -->
            


            <ul class="list-group mb-4">
                <li v-for="task in tasks" :key="task.id" class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h5  class="mb-1">{{ task.title }}</h5>
                        <p class="mb-1">{{ task.description }}</p>
                        <small class="text-muted">Assigned to: {{ task.user }}</small>
                    </div>
                    <div>
                        <button class="btn btn-success btn-sm mr-2" @click="completeTask(task.id)">Complete</button>
                        <button class="btn btn-danger btn-sm" @click="deleteTask(task.id)">Delete</button>
                    </div>
                </li>
            </ul>
    
            <form @submit.prevent="addTask" class="card card-body">
                <div class="form-group">
                    <input v-model="newTask.title" class="form-control" placeholder="Task Title" required>
                </div>
                <div class="form-group">
                    <input v-model="newTask.description" class="form-control" placeholder="Task Description" required>
                </div>
                <div class="form-group">
                    <input v-model="newTask.user" class="form-control" placeholder="Assigned User" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Add Task</button>
            </form>
        </div>
    </template>
    

<script>
    import { mapState, mapActions } from 'vuex';
    
    export default {
        data() {
            return {
                newTask: {
                    title: '',
                    description: '',
                    user: ''
                },
                showAll: false // Estado para controlar la vista de todas las tareas
            };
        },
        computed: {
            ...mapState(['tasks'])
        },
        mounted: function() {
            this.fetchTasks();
        },
        methods: {
            ...mapActions(['fetchTasks', 'addTask', 'completeTask', 'deleteTask','showAssignedTasks','showAllTasks']),
            addTask() {
                if (!this.newTask.title || !this.newTask.description || !this.newTask.user) {
                    alert('Both title and description are required');
                    return;
                }
    
                this.$store.dispatch('addTask', this.newTask).then(() => {
                    this.newTask.title = '';
                    this.newTask.description = '';
                    this.newTask.user = '';
                }).catch(error => {
                    console.error('Error adding task:', error);
                });
            },
            completeTask(taskId) {
                this.$store.dispatch('completeTask', taskId).catch(error => {
                    console.error('Error completing task:', error);
                });
            },
            deleteTask(taskId) {
                this.$store.dispatch('deleteTask', taskId).catch(error => {
                    console.error('Error deleting task:', error);
                });
            },
            showAssignedTasks() {
                this.showAll = false;
                this.fetchTasks(); // Llama a la acción para obtener las tareas del usuario logueado
            },
            showAllTasks() {
                this.showAll = true;
                this.fetchTasks(true); // Llama a la acción para obtener todas las tareas
            }
        },
        mounted() {
            this.fetchTasks(); // Cargar las tareas al montar el componente
        }
    };
</script>
    
