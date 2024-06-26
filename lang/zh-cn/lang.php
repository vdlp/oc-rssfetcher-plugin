<?php

declare(strict_types=1);

return [
    'plugin' => [
        'name' => 'RSS获取器',
        'description' => '从各种来源获取RSS项目，并将其放在您的网站上',
    ],
    'permissions' => [
        'access_sources' => '管理来源',
        'access_items' => '管理项目',
        'access_import_export' => '允许导入和导出',
        'access_feeds' => '管理订阅',
    ],
    'navigation' => [
        'menu_label' => 'RSS获取器',
        'side_menu_label_sources' => '来源',
        'side_menu_label_items' => '项目',
        'side_menu_label_feeds' => '订阅',
    ],
    'source' => [
        'source' => '来源',
        'sources' => '来源',
        'name' => '名称',
        'source_url' => '来源URL',
        'source_id' => '来源ID',
        'description' => '描述',
        'enabled' => '启用',
        'enabled_comment' => '点击此开关以启用此RSS来源',
        'items_count' => '项目',
        'last_fetched' => '上次获取',
        'max_items' => '最大项目数',
        'max_items_description' => '从来源获取的最大项目数',
        'publish_new_items' => '发布新获取的项目',
        'source_not_enabled' => '来源未启用，请先启用它',
        'items_fetch_success' => '已成功从此来源获取RSS项目',
        'items_fetch_fail' => '在获取RSS项目时发生错误：:error',
        'new_source' => '新来源',
        'create_source' => '创建来源',
        'edit_source' => '编辑来源',
        'manage_sources' => '管理来源',
        'return_to_sources' => '返回来源列表',
        'fetch_items' => '获取项目',
        'fetching_items' => '正在获取项目...',
        'delete_confirm' => '您确定吗？',
        'import_sources' => '导入来源',
        'export_sources' => '导出来源',
        'update_existing_sources' => '更新现有来源',
        'update_existing_sources_comment' => '勾选此框以更新具有相同ID的来源。',
    ],
    'item' => [
        'item' => '项目',
        'items' => '项目',
        'items_per_page' => '每页项目数',
        'items_per_page_validation' => '每页项目数值的格式无效',
        'new_item' => '新项目',
        'create_item' => '创建项目',
        'edit_item' => '编辑项目',
        'manage_items' => '管理项目',
        'return_to_items' => '返回项目列表',
        'delete_confirm' => '您确定吗？',
        'hide_published' => '隐藏已发布',
        'import_sources' => '导入来源',
        'export_sources' => '导出来源',
        'publish' => '发布',
        'unpublish' => '取消发布',
        'enclosure_url' => '附件URL',
        'enclosure_length' => '附件长度',
        'enclosure_type' => '附件类型',
        'title' => '标题',
        'link' => '链接',
        'description' => '描述',
        'author' => '作者',
        'category' => '类别',
        'comments' => '评论',
        'published_at' => '发布时间',
        'is_published' => '已发布',
        'is_published_comment' => '点击此开关以发布此项目',
    ],
    'feed' => [
        'feed' => '订阅',
        'feeds' => '订阅',
        'title' => '标题',
        'type' => '类型',
        'description' => '描述',
        'path' => '路径',
        'path_placeholder' => 'path/to/feed',
        'path_comment' => '输入此订阅应该可用的相对路径。例如：news/financial/latest',
        'sources' => '来源',
        'sources_comment' => '选择应包含在此订阅中的来源',
        'enabled' => '启用',
        'enabled_comment' => '点击此开关以启用此订阅',
        'max_items' => '订阅中的最大项目数',
        'new_feed' => '新订阅',
        'return_to_feeds' => '返回订阅列表',
        'delete_confirm' => '您确定吗？',
        'manage_feeds' => '管理订阅',
        'create_feed' => '创建订阅',
        'edit_feed' => '编辑订阅',
    ],
    'component' => [
        'item_list' => [
            'name' => '项目列表',
            'description' => '显示最新的RSS项目列表。',
        ],
        'paginatable_item_list' => [
            'name' => '可分页的项目列表',
            'description' => '显示可分页的RSS项目列表。',
        ],
        'source_list' => [
            'name' => '来源列表',
            'description' => '显示来源列表。',
        ],
    ],
    'report_widget' => [
        'headlines' => [
            'name' => 'RSS项目小部件',
            'description' => '显示最新获取的RSS项目',
            'title_title' => '小部件标题',
            'title_default' => '最新头条',
            'title_required' => '需要小部件标题',
            'max_items_title' => '要显示的项目数',
            'date_format_title' => '日期格式',
            'date_format_description' => '请检查php.net上的官方PHP文档以获取日期格式说明。',
        ],
    ],
];
