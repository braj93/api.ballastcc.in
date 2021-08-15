import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddBroadcastComponent } from './add-broadcast.component';

describe('AddBroadcastComponent', () => {
  let component: AddBroadcastComponent;
  let fixture: ComponentFixture<AddBroadcastComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddBroadcastComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddBroadcastComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
