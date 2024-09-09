import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios'; // Asegúrate de tener axios instalado

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        tasks: [] // Estado inicial para las tareas
    },
    mutations: {
        ADD_TASK(state, task) {
            state.tasks.push(task);
        },
        UPDATE_TASK(state, updatedTask) {
            const index = state.tasks.findIndex(t => t.id === updatedTask.id);
            if (index !== -1) {
                Vue.set(state.tasks, index, updatedTask);
            }
        },
        SET_TASKS(state, tasks) {
            state.tasks = tasks;
        },
        DELETE_TASK(state, taskId) {
            state.tasks = state.tasks.filter(t => t.id !== taskId);
        }
    },
    actions: {
        fetchTasks({ commit }, showAll = false) {
            try {
                axios.get('/tasks/index', { params: { showAll } })                
                .then(response => {
                    commit('SET_TASKS', response.data);
                })
                .catch(error => {
                    console.error("Error adding task:", error);
                });
            } catch (error) {
                console.error("Error fetching tasks:", error);
            }
        },
        addTask({ commit }, task) {
            axios.post('/tasks', task)
                .then(response => {
                    commit('ADD_TASK', response.data);
                })
                .catch(error => {
                    console.error("Error adding task:", error);
                });
        },
        updateTask({ commit }, task) {
            axios.put(`/tasks/${task.id}`, {
                title: task.title,
                description: task.description,
                completed: task.completed // Asegurarse de que el campo 'completed' se envía
            })
            .then(response => {
                commit('UPDATE_TASK', response.data);
            })
            .catch(error => {
                console.error("Error updating task:", error);
            });
        
        },
        deleteTask({ commit }, taskId) {
            axios.delete(`/tasks/${taskId}`)
                .then(() => {
                    commit('DELETE_TASK', taskId);
                })
                .catch(error => {
                    console.error("Error deleting task:", error);
                });
        },
        completeTask({ commit }, taskId) {
            axios.put(`/tasks/${taskId}/complete`)
                .then(response => {
                    commit('UPDATE_TASK', response.data.task); // Actualizar la tarea
                })
                .catch(error => {
                    console.error("Error completing task:", error);
                });
        }
    },
    getters: {
        tasks: state => state.tasks
    }
});
