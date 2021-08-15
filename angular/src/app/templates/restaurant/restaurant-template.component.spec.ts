import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RestaurantTemplateComponent } from './restaurant-template.component';

describe('RestaurantTemplateComponent', () => {
  let component: RestaurantTemplateComponent;
  let fixture: ComponentFixture<RestaurantTemplateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RestaurantTemplateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RestaurantTemplateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
