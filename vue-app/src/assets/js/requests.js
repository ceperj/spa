import api from "./api";

export default {
    async getBanksDropdown(){ return api.getJSON('/api/banks/dd'); },
    
    async getBatteries(){ return api.getJSON('/api/batteries'); },
    async getBatteriesDropdown(){ return api.getJSON('/api/batteries/dd'); },
    
    async getInssUniqueAliquot(){ return api.getJSON('/api/inss/unique'); },
    
    async getIrpf(){ return api.getJSON('/api/irpf'); },

    async getJob(id){ return api.getJSON(`/api/job/${id}`); },
    async getJobs(){ return api.getJSON('/api/jobs'); },
    async getJobsDropdown(){ return api.getJSON('/api/jobs/dd'); },

    async getPerson(id){ return api.getJSON(`/api/person/${id}`); },
    async getPersons(page){ return api.getJSON(`/api/person?page=${page|0}`); },
    
    async getProject(id){ return api.getJSON(`/api/project/${id}`); },
    async getProjects(page){ return api.getJSON(`/api/projects?page=${page|0}`); },
    async getProjectsDropdown(){ return api.getJSON('/api/projects/dd'); },

    async getUser(id){ return api.getJSON(`/api/user/${id}`) },
    async getUsers(){ return api.getJSON(`/api/users`) },
}