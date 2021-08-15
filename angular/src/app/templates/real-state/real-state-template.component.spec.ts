import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RealStateTemplateComponent } from './real-state-template.component';

describe('RealStateTemplateComponent', () => {
  let component: RealStateTemplateComponent;
  let fixture: ComponentFixture<RealStateTemplateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RealStateTemplateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RealStateTemplateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
