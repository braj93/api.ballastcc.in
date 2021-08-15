import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ViewProileComponent } from './view-profile.component';

describe('ViewProileComponent', () => {
  let component: ViewProileComponent;
  let fixture: ComponentFixture<ViewProileComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ViewProileComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ViewProileComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
