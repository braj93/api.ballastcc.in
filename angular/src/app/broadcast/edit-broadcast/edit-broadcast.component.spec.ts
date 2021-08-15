import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { EditBroadcastComponent } from './edit-broadcast.component';

describe('EditBroadcastComponent', () => {
  let component: EditBroadcastComponent;
  let fixture: ComponentFixture<EditBroadcastComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ EditBroadcastComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(EditBroadcastComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
