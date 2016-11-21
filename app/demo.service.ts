import {Injectable} from '@angular/core';
import {HTTP_PROVIDERS, Http, Response, Headers, RequestOptions} from "@angular/http";
import {Observable} from 'rxjs/Rx';

@Injectable()
export class DemoService {

    constructor(private http:Http) {
    }

    // Uses http.get() to load a single JSON file
    getFoods() {
        return this.http.get('http://localhost/angular2-swagger/food/').map((res:Response) => res.json());
    }

    createFood(food) {
        let headers = new Headers({'Content-Type': 'application/json'});
        let options = new RequestOptions({headers: headers});
        let body = JSON.stringify(food);
        console.log(body);
        // Note: This is only an example. The following API call will fail because there is no actual API to talk to.
        return this.http.post('http://localhost/angular2-swagger/food/', body, headers).map((res:Response) => res.json());
    }

    updateFood(food) {
        let headers = new Headers({'Content-Type': 'application/json'});
        let options = new RequestOptions({headers: headers});
        let body = JSON.stringify(food);
        // Note: This is only an example. The following API call will fail because there is no actual API to talk to.
        return this.http.put('http://localhost/angular2-swagger/food/', body, headers).map((res:Response) => res.json());
    }

    deleteFood(food) {
        // Note: This is only an example. The following API call will fail because there is no actual API to talk to.
        let body = JSON.stringify(food);
        return this.http.delete('http://localhost/angular2-swagger/food/' + food.food_id);
    }

}
