import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewBroadcastComponent } from './view-broadcast.component';

describe('ViewBroadcastComponent', () => {
  let component: ViewBroadcastComponent;
  let fixture: ComponentFixture<ViewBroadcastComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ViewBroadcastComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ViewBroadcastComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
