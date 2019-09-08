/**
 * An easy tree view plugin for jQuery and Bootstrap
 * @Copyright yuez.me 2014
 * @Author yuez
 * @Version 0.1
 */
(function ($) {
    $.fn.EasyTree = function (options) {
        var defaults = {
            selectable: true,
            deletable: false,
            editable: false,
            addable: false,
            addable_assembly:false,
            addable_component:false,
            addable_interaction:false,
            addable_attributes:false,
            i18n: {
                deleteNull: 'Select a node to delete',
                deleteConfirmation: 'Delete this node?',
                confirmButtonLabel: 'Okay',
                editNull: 'Select a node to edit',
                editMultiple: 'Only one node can be edited at one time',
                addMultiple: 'Select a node to add a new node',
                collapseTip: 'collapse',
                expandTip: 'expand',
                selectTip: 'select',
                unselectTip: 'unselet',
                editTip: 'edit',
                addTip: 'add',
                deleteTip: 'delete',
                cancelButtonLabel: 'cancel'
            }
        };
        
       
        var warningAlert = $('<div class="alert alert-warning alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong></strong><span class="alert-content"></span> </div> ');
        var dangerAlert = $('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong></strong><span class="alert-content"></span> </div> ');
        //creates the text box for creating a new node.
        var createInput = $('<div class="input-group"><input type="text" name="package" class="form-control"><span class="input-group-btn"><button onclick="addCancelClicked()" type="button" class="btn btn-default btn-success confirm"></button> </span><span class="input-group-btn"><button type="button" class="btn btn-default cancel"></button> </span> </div> ');
        
        var createAssembly = $('<div class="input-group"><input type="text" name="device" class="form-control"><span class="input-group-btn"><button onclick="addCancelClicked()" type="button" class="btn btn-default btn-success confirm"></button> </span><span class="input-group-btn"><button type="button" class="btn btn-default cancel"></button> </span> </div> ');
        
        var createComponent = $('<div class="input-group"><input type="text" name="assembly" class="form-control"><span class="input-group-btn"><button onclick="addCancelClicked()" type="button" class="btn btn-default btn-success confirm"></button> </span><span class="input-group-btn"><button type="button" class="btn btn-default cancel"></button> </span> </div> ');
        
        var createInteraction = $('<div class="input-group"><input type="text" name="component" class="form-control"><span class="input-group-btn"><button type="button" onclick="addCancelClicked()" class="btn btn-default btn-success confirm"></button> </span><span class="input-group-btn"><button type="button" class="btn btn-default cancel"></button> </span> </div> ');
        
        var createAttributes = $('<div class="input-group"><input type="text" name="subcomponent" class="form-control"><span class="input-group-btn"><button type="button" onclick="addCancelClicked()" class="btn btn-default btn-success confirm"></button> </span><span class="input-group-btn"><button type="button" class="btn btn-default cancel"></button> </span> </div> ');

        options = $.extend(defaults, options);

        this.each(function () {
            var easyTree = $(this);
            $.each($(easyTree).find('ul > li'), function() {
                var text;
                if($(this).is('li:has(ul)')) {
                    var children = $(this).find(' > ul');
                    $(children).remove();
                    text = $(this).text();
                    $(this).html('<span><span class="glyphicon"></span><a href="javascript: void(0);"></a> </span>');
                    $(this).find(' > span > span').addClass('glyphicon-folder-open');
                    $(this).find(' > span > a').text(text);
                    $(this).append(children);
                }
                else {
                    text = $(this).text();
                    $(this).html('<span><span class="glyphicon"></span><a href="javascript: void(0);"></a> </span>');
                    $(this).find(' > span > span').addClass('glyphicon-file');
                    $(this).find(' > span > a').text(text);
                }
            });

            $(easyTree).find('li:has(ul)').addClass('parent_li').find(' > span').attr('title', options.i18n.collapseTip);

            // add easy tree toolbar dom
            if (options.deletable || options.editable || options.addable || options.addable_assembly ||options.addable_component || options.addable_interaction || options.addable_attributes) {
                $(easyTree).prepend('<div class="easy-tree-toolbar"></div> ');
            }

            // addable
            if (options.addable) {
                $(easyTree).find('.easy-tree-toolbar').append('<div class="create"><button class="btn btn-default btn-sm btn-success" style="display: none;"><span class="glyphicon glyphicon-plus"></span> device </button></div> ');
                $(easyTree).find('.easy-tree-toolbar .create > button').attr('title', options.i18n.addTip).click(function () {
                    

                    var createBlock = $(easyTree).find('.easy-tree-toolbar .create');
                    //generate Id
                   
                    var Id = Math.floor(Math.random()*75);
                    $(createBlock).append(createInput); 
                    //add id
                    $(createInput).attr('id','Id');
                    $(createInput).find('input').focus();
                    $(createInput).find('.confirm').text(options.i18n.confirmButtonLabel);
                    $(createInput).find('.confirm').click(function () {
                        if ($(createInput).find('input').val() === '')
                            return;
                        var selected = getSelectedItems();
                        var item = $('<li><id> device </id><span><span class="glyphicon glyphicon-file"></span><a href="javascript: void(0);"><device>' + $(createInput).find('input').val() + '</device></a></span></li>');
                        $(item).find(' > span > span').attr('title', options.i18n.collapseTip);
                        $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                       // $(item).append('<device></device>');
                       
                        
                      //  $(item).find(' > span > a').attr('id','ID')
                        if (selected.length <= 0) {
                            $(easyTree).find(' > ul').append($(item));
                        } else if (selected.length > 1) {
                            $(easyTree).prepend(warningAlert);
                            $(easyTree).find('.alert .alert-content').text(options.i18n.addMultiple);
                        } else {
                            if ($(selected).hasClass('parent_li')) {
                                $(selected).find(' > ul').append(item);
                            } else {
                                $(selected).addClass('parent_li').find(' > span > span').addClass('glyphicon-folder-open').removeClass('glyphicon-file');
                                $(selected).append($('<ul></ul>')).find(' > ul').append(item);
                            }
                        }
                        $(createInput).find('input').val('');
                        if (options.selectable) {
                            $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                            $(item).find(' > span > a').click(function (e) {
                                var li = $(this).parent().parent();
                             //   var selected = getSelectedItems();
                              //  if (li.hasClass('li_selected')&& selected.length > 2) {
                                  if (li.hasClass('li_selected')) {
                                    $(this).attr('title', options.i18n.selectTip);
                                    $(li).removeClass('li_selected');
                                }
                                else {
                                    $(easyTree).find('li.li_selected').removeClass('li_selected');
                                    $(this).attr('title', options.i18n.unselectTip);
                                    $(li).addClass('li_selected');
                                }

                                if (options.deletable || options.editable || options.addable) {
                                    var selected = getSelectedItems();
                                    if (options.editable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').removeClass('disabled');
                                    }

                                    if (options.deletable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').removeClass('disabled');
                                    }

                                }

                                e.stopPropagation();

                            });
                        }
                        $(createInput).remove();
                    });
                    $(createInput).find('.cancel').text(options.i18n.cancelButtonLabel);
                    $(createInput).find('.cancel').click(function () {
                        $(createInput).remove();
                    });
                });
            }
            
             // addable
            if (options.addable_assembly) {
                $(easyTree).find('.easy-tree-toolbar').append('<div class="create_assembly"><button class="btn btn-default btn-sm btn-success" style="display:none;"><span class="glyphicon glyphicon-plus"></span> assembly </button></div> ');
                $(easyTree).find('.easy-tree-toolbar .create_assembly > button').attr('title', options.i18n.addTip).click(function () {
                    

                    var createBlock2 = $(easyTree).find('.easy-tree-toolbar .create_assembly');
                    //generate Id
                   
                    var Id = Math.floor(Math.random()*75);
                    $(createBlock2).append(createAssembly); 
                    //add id
                    $(createAssembly).attr('id','Id');
                    $(createAssembly).find('input').focus();
                    $(createAssembly).find('.confirm').text(options.i18n.confirmButtonLabel);
                    $(createAssembly).find('.confirm').click(function () {
                        if ($(createAssembly).find('input').val() === '')
                            return;
                        var selected = getSelectedItems();
                        var item = $('<li><id> assembly </id><span><span class="glyphicon glyphicon-file"></span><a href="javascript: void(0);"><classA>' + $(createAssembly).find('input').val() + '</classA></a></span></li>');
                        $(item).find(' > span > span').attr('title', options.i18n.collapseTip);
                        $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                      //  $(item).find(' > span > a').attr('id','ID')
                        if (selected.length <= 0) {
                            $(easyTree).find(' > ul').append($(item));
                        } else if (selected.length > 1) {
                            $(easyTree).prepend(warningAlert);
                            $(easyTree).find('.alert .alert-content').text(options.i18n.addMultiple);
                        } else {
                            if ($(selected).hasClass('parent_li')) {
                                $(selected).find(' > ul').append(item);
                            } else {
                                $(selected).addClass('parent_li').find(' > span > span').addClass('glyphicon-folder-open').removeClass('glyphicon-file');
                                $(selected).append($('<ul></ul>')).find(' > ul').append(item);
                            }
                        }
                        $(createAssembly).find('input').val('');
                        if (options.selectable) {
                            $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                            $(item).find(' > span > a').click(function (e) {
                                var li = $(this).parent().parent();
                                if (li.hasClass('li_selected')) {
                                    $(this).attr('title', options.i18n.selectTip);
                                    $(li).removeClass('li_selected');
                                }
                                else {
                                    $(easyTree).find('li.li_selected').removeClass('li_selected');
                                    $(this).attr('title', options.i18n.unselectTip);
                                    $(li).addClass('li_selected');
                                }

                                if (options.deletable || options.editable || options.addable || options.addable_assembly) {
                                    var selected = getSelectedItems();
                                    if (options.editable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').removeClass('disabled');
                                    }

                                    if (options.deletable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').removeClass('disabled');
                                    }

                                }

                                e.stopPropagation();

                            });
                        }
                        $(createAssembly).remove();
                    });
                    $(createAssembly).find('.cancel').text(options.i18n.cancelButtonLabel);
                    $(createAssembly).find('.cancel').click(function () {
                        $(createAssembly).remove();
                    });
                });
            }
            
              // addable
            if (options.addable_component) {
                $(easyTree).find('.easy-tree-toolbar').append('<div class="create_component"><button class="btn btn-default btn-sm btn-success" style="display: none;"><span class="glyphicon glyphicon-plus"></span> component </button></div> ');
                $(easyTree).find('.easy-tree-toolbar .create_component > button').attr('title', options.i18n.addTip).click(function () {
                    

                    var createBlock3 = $(easyTree).find('.easy-tree-toolbar .create_component');
                    //generate Id
                   
                    var Id = Math.floor(Math.random()*75);
                    $(createBlock3).append(createComponent); 
                    //add id
                    $(createComponent).attr('id','Id');
                    $(createComponent).find('input').focus();
                    $(createComponent).find('.confirm').text(options.i18n.confirmButtonLabel);
                    $(createComponent).find('.confirm').click(function () {
                        if ($(createComponent).find('input').val() === '')
                            return;
                        var selected = getSelectedItems();
                        var item = $('<li><id>component</id><span><span class="glyphicon glyphicon-file"></span><a href="javascript: void(0);"><component>' + $(createComponent).find('input').val() + '</component></a></span></li>');
                        $(item).find(' > span > span').attr('title', options.i18n.collapseTip);
                        $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                      //  $(item).find(' > span > a').attr('id','ID')
                        if (selected.length <= 0) {
                            $(easyTree).find(' > ul').append($(item));
                        } else if (selected.length > 1) {
                            $(easyTree).prepend(warningAlert);
                            $(easyTree).find('.alert .alert-content').text(options.i18n.addMultiple);
                        } else {
                            if ($(selected).hasClass('parent_li')) {
                                $(selected).find(' > ul').append(item);
                            } else {
                                $(selected).addClass('parent_li').find(' > span > span').addClass('glyphicon-folder-open').removeClass('glyphicon-file');
                                $(selected).append($('<ul></ul>')).find(' > ul').append(item);
                            }
                        }
                        $(createComponent).find('input').val('');
                        if (options.selectable) {
                            $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                            $(item).find(' > span > a').click(function (e) {
                                var li = $(this).parent().parent();
                                if (li.hasClass('li_selected')) {
                                    $(this).attr('title', options.i18n.selectTip);
                                    $(li).removeClass('li_selected');
                                }
                                else {
                                    $(easyTree).find('li.li_selected').removeClass('li_selected');
                                    $(this).attr('title', options.i18n.unselectTip);
                                    $(li).addClass('li_selected');
                                }

                                if (options.deletable || options.editable || options.addable || options.addable_assembly || options.addable_component) {
                                    var selected = getSelectedItems();
                                    if (options.editable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').removeClass('disabled');
                                    }

                                    if (options.deletable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').removeClass('disabled');
                                    }

                                }

                                e.stopPropagation();

                            });
                        }
                        $(createComponent).remove();
                    });
                    $(createComponent).find('.cancel').text(options.i18n.cancelButtonLabel);
                    $(createComponent).find('.cancel').click(function () {
                        $(createComponent).remove();
                    });
                });
            }
            
           
             // addable
            if (options.addable_attributes) {
                $(easyTree).find('.easy-tree-toolbar').append('<div class="create_attributes"><button class="btn btn-default btn-sm btn-success" style="display: none;" ><span class="glyphicon glyphicon-plus"></span> attribute </button></div> ');
                $(easyTree).find('.easy-tree-toolbar .create_attributes > button').attr('title', options.i18n.addTip).click(function () {
                    

                    var createBlock4 = $(easyTree).find('.easy-tree-toolbar .create_attributes');
                    //generate Id
                   
                    var Id = Math.floor(Math.random()*75);
                    $(createBlock4).append(createAttributes); 
                    //add id
                    $(createAttributes).attr('id','Id');
                    $(createAttributes).find('input').focus();
                    $(createAttributes).find('.confirm').text(options.i18n.confirmButtonLabel);
                    $(createAttributes).find('.confirm').click(function () {
                        if ($(createAttributes).find('input').val() === '')
                            return;
                        var selected = getSelectedItems();
                        var item = $('<li><id>attribute</id><span><span class="glyphicon glyphicon-file"></span><a href="javascript: void(0);"><classattr>' + $(createAttributes).find('input').val() + '</classattr></a></span></li>');
                        $(item).find(' > span > span').attr('title', options.i18n.collapseTip);
                        $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                      //  $(item).find(' > span > a').attr('id','ID')
                        if (selected.length <= 0) {
                            $(easyTree).find(' > ul').append($(item));
                        } else if (selected.length > 1) {
                            $(easyTree).prepend(warningAlert);
                            $(easyTree).find('.alert .alert-content').text(options.i18n.addMultiple);
                        } else {
                            if ($(selected).hasClass('parent_li')) {
                                $(selected).find(' > ul').append(item);
                            } else {
                                $(selected).addClass('parent_li').find(' > span > span').addClass('glyphicon-folder-open').removeClass('glyphicon-file');
                                $(selected).append($('<ul></ul>')).find(' > ul').append(item);
                            }
                        }
                        $(createAttributes).find('input').val('');
                        if (options.selectable) {
                            $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                            $(item).find(' > span > a').click(function (e) {
                                var li = $(this).parent().parent();
                                if (li.hasClass('li_selected')) {
                                    $(this).attr('title', options.i18n.selectTip);
                                    $(li).removeClass('li_selected');
                                }
                                else {
                                    $(easyTree).find('li.li_selected').removeClass('li_selected');
                                    $(this).attr('title', options.i18n.unselectTip);
                                    $(li).addClass('li_selected');
                                }

                                if (options.deletable || options.editable || options.addable || options.addable_assembly || options.addable_component || options.addable_interaction || options.addable_attributes) {
                                    var selected = getSelectedItems();
                                    if (options.editable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').removeClass('disabled');
                                    }

                                    if (options.deletable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').removeClass('disabled');
                                    }

                                }

                                e.stopPropagation();

                            });
                        }
                        $(createAttributes).remove();
                    });
                    $(createAttributes).find('.cancel').text(options.i18n.cancelButtonLabel);
                    $(createAttributes).find('.cancel').click(function () {
                        $(createAttributes).remove();
                    });
                });
            }
            
            // addable
            if (options.addable_interaction) {
                $(easyTree).find('.easy-tree-toolbar').append('<div class="create_interaction"><button class="btn btn-default btn-sm btn-success" style="display: none;"><span class="glyphicon glyphicon-plus"></span> interaction </button></div> ');
                $(easyTree).find('.easy-tree-toolbar .create_interaction > button').attr('title', options.i18n.addTip).click(function () {
                    

                    var createBlock4 = $(easyTree).find('.easy-tree-toolbar .create_interaction');
                    //generate Id
                   
                    var Id = Math.floor(Math.random()*75);
                    $(createBlock4).append(createInteraction); 
                    //add id
                    $(createInteraction).attr('id','Id');
                    $(createInteraction).find('input').focus();
                    $(createInteraction).find('.confirm').text(options.i18n.confirmButtonLabel);
                    $(createInteraction).find('.confirm').click(function () {
                        if ($(createInteraction).find('input').val() === '')
                            return;
                        var selected = getSelectedItems();
                        var item = $('<li><id>interaction</id><span><span class="glyphicon glyphicon-file"></span><a href="javascript: void(0);"><interaction>' + $(createInteraction).find('input').val() + '</interaction></a></span></li>');
                        $(item).find(' > span > span').attr('title', options.i18n.collapseTip);
                        $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                      //  $(item).find(' > span > a').attr('id','ID')
                        if (selected.length <= 0) {
                            $(easyTree).find(' > ul').append($(item));
                        }// else if (selected.length > 1) {
                         else if(selected.length > 2){
                            $(easyTree).prepend(warningAlert);
                            $(easyTree).find('.alert .alert-content').text(options.i18n.addMultiple);
                        } else if(selected.length <= 2) {
                            if ($(selected).hasClass('parent_li')) {
                                $(selected).find(' > ul').append(item);
                            } else {
                                $(selected).addClass('parent_li').find(' > span > span').addClass('glyphicon-folder-open').removeClass('glyphicon-file');
                                $(selected).append($('<ul></ul>')).find(' > ul').append(item);
                            }
                        }
                        $(createInteraction).find('input').val('');
                        if (options.selectable) {
                            $(item).find(' > span > a').attr('title', options.i18n.selectTip);
                            $(item).find(' > span > a').click(function (e) {
                                var li = $(this).parent().parent();
                                // find selected li
                                var selected_li = $(easyTree).find('li.li_selected');
                            
                                if (selected_li.length === 2) {
                                    $(this).attr('title', options.i18n.selectTip);
                                    $(li).removeClass('li_selected');
                                    $(easyTree).find('li.li_selected').removeClass('li_selected');   
                                    $(this).attr('title', options.i18n.unselectTip);
                                    $(li).addClass('li_selected');
                                } 
                                
                                else if(selected_li.length === 1){
                                    $(easyTree).find('li.li_selected');                              
                                    $(this).attr('title', options.i18n.unselectTip);
                                    $(li).addClass('li_selected');
                                }
                                else{
                                    $(easyTree).find('li.li_selected').removeClass('li_selected');                              
                                    $(this).attr('title', options.i18n.unselectTip);
                                    $(li).addClass('li_selected');
                                }

                                if (options.deletable || options.editable || options.addable || options.addable_assembly || options.addable_component || options.addable_interaction) {
                                    var selected = getSelectedItems();
                                    if (options.editable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .edit > button').removeClass('disabled');
                                    }

                                    if (options.deletable) {
                                        if (selected.length <= 0 || selected.length > 1)
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                                        else
                                            $(easyTree).find('.easy-tree-toolbar .remove > button').removeClass('disabled');
                                    }

                                }

                                e.stopPropagation();

                            });
                        }
                        $(createInteraction).remove();
                    });
                    $(createInteraction).find('.cancel').text(options.i18n.cancelButtonLabel);
                    $(createInteraction).find('.cancel').click(function () {
                        $(createInteraction).remove();
                    });
                });
            }
            
         // add dropdown
         
         	$(easyTree).find('.easy-tree-toolbar').append("<select id='selectAddable' style='width: 10%;height: 35px;padding: 6px 12px;color: #555;'><option value=''>-- Select -- </option><option value='create'>Device</option><option value='create_assembly'>Assembly</option><option value='create_component'>Component</option><option value='create_attribute'>Attribute</option><option value='create_interaction'>Interaction</option></select>");   
            
            
            // editable
            if (options.editable) {
                $(easyTree).find('.easy-tree-toolbar').append('<div class="edit"><button class="btn btn-default btn-sm btn-primary disabled"><span class="glyphicon glyphicon-edit"></span></button></div> ');
                $(easyTree).find('.easy-tree-toolbar .edit > button').attr('title', options.i18n.editTip).click(function () {
                    $(easyTree).find('input.easy-tree-editor').remove();
                    $(easyTree).find('li > span > a:hidden').show();
                    var selected = getSelectedItems();
                    if (selected.length <= 0) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.editNull);
                    }
                    else if (selected.length > 1) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.editMultiple);
                    }
                    else {
                        var value = $(selected).find(' > span > a').text();
                        $(selected).find(' > span > a').hide();
                        $(selected).find(' > span').append('<input type="text" class="easy-tree-editor">');
                        var editor = $(selected).find(' > span > input.easy-tree-editor');
                        $(editor).val(value);
                        $(editor).focus();
                        $(editor).keydown(function (e) {
                            if (e.which == 13) {
                                if ($(editor).val() !== '') {
                                    $(selected).find(' > span > a').text($(editor).val());
                                    $(editor).remove();
                                    $(selected).find(' > span > a').show();
                                }
                            }
                        });
                    }
                });
            }

            // deletable
            if (options.deletable) {
                $(easyTree).find('.easy-tree-toolbar').append('<div class="remove"><button class="btn btn-default btn-sm btn-danger disabled"><span class="glyphicon glyphicon-remove"></span></button></div> ');
                $(easyTree).find('.easy-tree-toolbar .remove > button').attr('title', options.i18n.deleteTip).click(function () {
                    var selected = getSelectedItems();
                    if (selected.length <= 0) {
                        $(easyTree).prepend(warningAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.deleteNull);
                    } else {
                        $(easyTree).prepend(dangerAlert);
                        $(easyTree).find('.alert .alert-content').html(options.i18n.deleteConfirmation)
                            .append('<a style="margin-left: 10px;" class="btn btn-default btn-danger confirm"></a>')
                            .find('.confirm').html(options.i18n.confirmButtonLabel);
                        $(easyTree).find('.alert .alert-content .confirm').on('click', function () {
                            $(selected).find(' ul ').remove();
                            if($(selected).parent('ul').find(' > li').length <= 1) {
                                $(selected).parents('li').removeClass('parent_li').find(' > span > span').removeClass('glyphicon-folder-open').addClass('glyphicon-file');
                                $(selected).parent('ul').remove();
                            }
                            $(selected).remove();
                            $(dangerAlert).remove();
                        });
                    }
                });
            }

            // collapse or expand
            $(easyTree).delegate('li.parent_li > span', 'click', function (e) {
                var children = $(this).parent('li.parent_li').find(' > ul > li');
                if (children.is(':visible')) {
                    children.hide('fast');
                    $(this).attr('title', options.i18n.expandTip)
                        .find(' > span.glyphicon')
                        .addClass('glyphicon-folder-close')
                        .removeClass('glyphicon-folder-open');
                } else {
                    children.show('fast');
                    $(this).attr('title', options.i18n.collapseTip)
                        .find(' > span.glyphicon')
                        .addClass('glyphicon-folder-open')
                        .removeClass('glyphicon-folder-close');
                }
                e.stopPropagation();
            });

//            // selectable, only single select
//            if (options.selectable) {
//                $(easyTree).find('li > span > a').attr('title', options.i18n.selectTip);
//                $(easyTree).find('li > span > a').click(function (e) {
//                    var li = $(this).parent().parent();                 
//                    if (li.hasClass('li_selected')) {
//                        $(this).attr('title', options.i18n.selectTip);
//                        $(li).removeClass('li_selected');
//                        $(li).addClass('li_selected');                       
//                    }                   
//                    else {
//                        $(easyTree).find('li.li_selected').removeClass('li_selected');
//                        $(this).attr('title', options.i18n.unselectTip);
//                        $(li).addClass('li_selected');
//                    }
//                
//                    if (options.deletable || options.editable || options.addable) {
//                        var selected = getSelectedItems();
//                        if (options.editable) {
//                            if (selected.length <= 0 || selected.length > 1)
//                                $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
//                            else
//                                $(easyTree).find('.easy-tree-toolbar .edit > button').removeClass('disabled');
//                        }
//
//                        if (options.deletable) {
//                            if (selected.length <= 0 || selected.length > 1)
//                                $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
//                            else
//                                $(easyTree).find('.easy-tree-toolbar .remove > button').removeClass('disabled');
//                        }
//
//                    }
//
//                    e.stopPropagation();
//
//                });
//            }
//            
            
            //my code
            
             // selectable, only double select
            if (options.selectable) {
                $(easyTree).find('li > span > a').attr('title', options.i18n.selectTip);
                $(easyTree).find('li > span > a').click(function (e) {
                    var li = $(this).parent().parent(); 
                    var selected = getSelectedItems();
                    alert(selected.length);   
                    if (li.hasClass('li_selected') && selected.length >= 2) {                                                                 
                            $(this).attr('title', options.i18n.selectTip);    
                            $(li).removeClass('li_selected');
                            $(li).addClass('li_selected');                        
                    }                   
                    else if ( selected.length < 2) {                      
                        $(easyTree).find('li.li_selected');
                        $(this).attr('title', options.i18n.unselectTip);
                        $(li).addClass('li_selected');                   
                    }
                    else{
                       $(easyTree).find('li.li_selected').removeClass('li_selected');
                        $(this).attr('title', options.i18n.unselectTip);
                        $(li).addClass('li_selected');  
                    }
                
                    if (options.deletable || options.editable || options.addable) {
                        var selected = getSelectedItems();
                        if (options.editable) {
                            if (selected.length <= 0 || selected.length > 1)
                                $(easyTree).find('.easy-tree-toolbar .edit > button').addClass('disabled');
                            else
                                $(easyTree).find('.easy-tree-toolbar .edit > button').removeClass('disabled');
                        }

                        if (options.deletable) {
                            if (selected.length <= 0 || selected.length > 1)
                                $(easyTree).find('.easy-tree-toolbar .remove > button').addClass('disabled');
                            else
                                $(easyTree).find('.easy-tree-toolbar .remove > button').removeClass('disabled');
                        }

                    }

                    e.stopPropagation();

                });
            }


            // Get selected items
            var getSelectedItems = function () {
                return $(easyTree).find('li.li_selected');
            };
        });
    };
})(jQuery);
