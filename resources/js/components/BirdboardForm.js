class BirdboardForm {
	
	constructor(data) {
		this.orginalData = JSON.parse(JSON.stringify(data));
		
		Object.assign(this, data);
	
		this.error = {};
		this.submitted = false;
	}


	data() {

		return Object.keys(this.orginalData).reduce((data, attribute) => {
			data[attribute] = this[attribute];

			return data;
		}, {});

	}

	
	post(endpoint) {
		this.submit(endpoint)
	}


	delete(endpoint) {
		this.submit(endpoint, 'delete')
	}

	patch(endpoint) {
		this.submit(endpoint, 'patch')
	}


	submit(endpoint, requestType = 'post') {

		return axios[requestType](endpoint, this.data())
			.catch(this.onFail.bind(this))
			.then(this.onSuccess.bind(this));

	}

	onSuccess(response) {

		this.submitted = true;
		this.error = {};

		return response;
	}

	onFail(error) {

		this.errors = error.response.data.errors;
		this.submitted = false;

		throw error;
	}


	reset() {

		Object.assign(this, this.orginalData);

	}

}


export default BirdboardForm;