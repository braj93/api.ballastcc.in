import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserPlanUpdateComponent } from './user-plan-update.component';

describe('UserPlanUpdateComponent', () => {
  let component: UserPlanUpdateComponent;
  let fixture: ComponentFixture<UserPlanUpdateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserPlanUpdateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserPlanUpdateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
