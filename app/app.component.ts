import {Component} from '@angular/core';
import {DemoService} from './demo.service';
import {Observable} from 'rxjs/Rx';

@Component({
  selector: 'demo-app',
  template:`
  <h1>Foods App</h1>
  <h2>Foods</h2>
  <ul>
    <li *ngFor="let food of foods"><input type="text" name="food_name" [(ngModel)]="food.food_name"><button (click)="updateFood(food)">Save</button> <button (click)="deleteFood(food)">Delete</button></li>
  </ul>
  <p>Create a new food: <input type="text" name="new_food" [(ngModel)]="new_food"><button (click)="createFood(new_food)">Save</button></p>
  
  `
})
export class AppComponent {

  public foods;

  public food_name;

  public new_food;

  constructor(private _demoService: DemoService) { }

  ngOnInit() {
    this.getFoods();
  }

  getFoods() {
    this._demoService.getFoods().subscribe(
      // the first argument is a function which runs on success
      data => { this.foods = data},
      // the second argument is a function which runs on error
      err => console.error(err),
      // the third argument is a function which runs on completion
      () => console.log('done loading foods')
    );
  }

  createFood(name) {
      console.log(name);
    let food = {food_name: name};
    this._demoService.createFood(food).subscribe(
       data => {
         // refresh the list
         this.getFoods();
         return true;
       },
       error => {
         console.error("Error saving food!");
         return Observable.throw(error);
       }
    );
  }

  updateFood(food) {
    this._demoService.updateFood(food).subscribe(
       data => {
         // refresh the list
         this.getFoods();
         return true;
       },
       error => {
         console.error("Error saving food!");
         return Observable.throw(error);
       }
    );
  }

  deleteFood(food) {
    if (confirm("Are you sure you want to delete " + food.food_name + "?")) {
      this._demoService.deleteFood(food).subscribe(
         data => {
           // refresh the list
           this.getFoods();
           return true;
         },
         error => {
           console.error("Error deleting food!");
           return Observable.throw(error);
         }
      );
    }
  }
}
