import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserCrmComponent } from './user-crm.component';

describe('UserCrmComponent', () => {
  let component: UserCrmComponent;
  let fixture: ComponentFixture<UserCrmComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserCrmComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserCrmComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
