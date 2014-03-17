//define(['App'], function(UMI){
//    'use strict';
//    return function(){
//
//        UMI.TableController = Ember.ArrayController.extend({
//            addRecord: function(){
//                this.controllerFor('TableRows').send('addRecord');
//            }
//        });
//
//        UMI.TableHeadersController = Ember.ArrayController.extend({
//            needs: 'tableRows',
//            content: function(){
//                return this.store.find('table_header');
//            }.property(),
//
//            removeRecords: function(){
//                //Вызываем метод removeRecords из контроллера rows
//                this.controllerFor('TableRows').send('removeRecords');
//            }
//        });
//
//        UMI.TableRowsController = Ember.ArrayController.extend({
//            needs: ['tableRow'],
//            sliceValue: 8,
//            content: function(){
//                return this.store.find('table_row');
//            }.property(),
//
//            addRecord: function(){
//                var name = this.get('newName');
//                if(!name.trim()){
//                    return;
//                }
//
//                var lastName = this.get('newLastName');
//                if(!lastName.trim()){
//                    return;
//                }
//
//                var email = this.get('newEmail');
//                if(!email.trim()){
//                    return;
//                }
//
//                var todo = this.store.createRecord('record', {
//                    name: name,
//                    lastName: lastName,
//                    email: email
//                });
//
//                this.set('newName', '');
//                this.set('newLastName', '');
//                this.set('newEmail', '');
//
//                todo.save();
//            },
//
//            removeRecords: function(){
//                var toRemove = this.filterProperty('isChecked', true);
//                //Выполняем методы для каждого объекта в вернувшемся массиве
//                toRemove.invoke('deleteRecord');
//                toRemove.invoke('save');
//            }
//
//            //			isChecked: function(key, value){
//            //				var model = this.get('model');
//            //				if(value === undefined){
//            //					return model.get('isChecked');
//            //				}else{
//            //					model.set('isChecked',value);
//            //					model.save();
//            //					return value;
//            //				}
//            //			}.property('model.isChecked'),
//            //
//            //			checkAll: function(key, value){
//            //				this.setEach('isChecked', value);
//            //			}.property()
//        });
//
//        UMI.TableRowController = Ember.ObjectController.extend({
//            cell: null,
//
//            init: function(){
//                var content = Ember.keys(Ember.meta(UMI.TableRow.proto()).descs); //Получаем список свойств модели
//                var row = [];
//
//                for(var i = 0; i < content.length; i++){
//                    row.push(this.get(content[i].toString()));
//                }
//                this.cell = row;
//            },
//
//            actions: {
//                editName: function(){
//                    this.set('isEditName', true);
//                },
//
//                editLastName: function(){
//                },
//
//                endEditName: function(){
//                    this.set('isEditName', false);
//                    $(this).val('text');
//                },
//
//                removeRecord: function(){
//                    var record = this.get('model');
//                    record.deleteRecord();
//                    record.save();
//                }
//            },
//
//            isEditing: false,
//            isEditName: false
//        });
//    };
//});