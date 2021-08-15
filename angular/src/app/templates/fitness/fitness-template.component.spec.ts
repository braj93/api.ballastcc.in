import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FitnessTemplateComponent } from './fitness-template.component';

describe('FitnessTemplateComponent', () => {
  let component: FitnessTemplateComponent;
  let fixture: ComponentFixture<FitnessTemplateComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FitnessTemplateComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FitnessTemplateComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
