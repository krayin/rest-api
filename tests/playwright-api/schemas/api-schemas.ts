import { z } from 'zod';

/**
 * Response contracts for the Krayin REST API (/api/v1).
 *
 * Kept deliberately tolerant on nullable relations — the goal is to catch a
 * changed or missing field, not to fail whenever an optional relation is absent.
 */

const id = z.number().int().positive();
const numeric = z.union([z.number(), z.string()]);
const timestamp = z.string().nullable().optional();

/* -------------------------------------------------------------------------- */
/* Envelopes                                                                   */
/* -------------------------------------------------------------------------- */

export const paginationMetaSchema = z.object({
  current_page: z.number().int().positive(),
  per_page: numeric,
  total: z.number().int().nonnegative(),
  last_page: z.number().int().positive(),
  from: z.number().int().nullable().optional(),
  to: z.number().int().nullable().optional(),
});

/** Paginated listing: `{ data: [...], meta, links }`. */
export const collectionSchema = <T extends z.ZodTypeAny>(item: T) =>
  z.object({
    data: z.array(item),
    meta: paginationMetaSchema.optional(),
    links: z.any().optional(),
  });

/** `pagination=0` listing: `{ data: [...] }` with no `meta`. */
export const unpaginatedCollectionSchema = <T extends z.ZodTypeAny>(item: T) =>
  z.object({ data: z.array(item) });

/** Single resource: `{ data: {...} }`. */
export const resourceSchema = <T extends z.ZodTypeAny>(item: T) => z.object({ data: item });

/** Write endpoints answer `{ data, message }`. */
export const mutationSchema = <T extends z.ZodTypeAny>(item: T) =>
  z.object({ data: item, message: z.string() });

export const messageSchema = z.object({ message: z.string() });

export const validationErrorSchema = z.object({
  message: z.string(),
  errors: z.record(z.string(), z.array(z.string())),
});

/* -------------------------------------------------------------------------- */
/* Resources                                                                   */
/* -------------------------------------------------------------------------- */

export const userSchema = z.object({
  id,
  name: z.string().min(1),
  email: z.string().email(),
  status: z.union([z.number(), z.boolean()]).nullable().optional(),
  role: z.any().nullable().optional(),
});

export const loginSchema = z.object({
  data: userSchema,
  message: z.string(),
  token: z.string().min(1),
});

export const personSchema = z.object({
  id,
  name: z.string().min(1),
  emails: z.any().nullable().optional(),
  contact_numbers: z.any().nullable().optional(),
  organization: z.any().nullable().optional(),
  created_at: timestamp,
});

export const organizationSchema = z.object({
  id,
  name: z.string().min(1),
  address: z.any().nullable().optional(),
  created_at: timestamp,
});

export const leadSchema = z.object({
  id,
  title: z.string().min(1),
  description: z.string().nullable().optional(),
  lead_value: numeric.nullable().optional(),
  status: z.number().int().nullable().optional(),
  created_at: timestamp,
  user: z.any().nullable().optional(),
  person: z.any().nullable().optional(),
  source: z.any().nullable().optional(),
  type: z.any().nullable().optional(),
  stage: z.any().nullable().optional(),
});

export const productSchema = z.object({
  id,
  sku: z.string().min(1),
  name: z.string().min(1),
  description: z.string().nullable().optional(),
  price: numeric.nullable().optional(),
  quantity: numeric.nullable().optional(),
  created_at: timestamp,
});

export const quoteSchema = z.object({
  id,
  subject: z.string().min(1),
  description: z.string().nullable().optional(),
  sub_total: numeric.nullable().optional(),
  grand_total: numeric.nullable().optional(),
  expired_at: z.string().nullable().optional(),
  created_at: timestamp,
});

export const activitySchema = z.object({
  id,
  title: z.string().nullable().optional(),
  type: z.string().nullable().optional(),
  comment: z.string().nullable().optional(),
  schedule_from: z.string().nullable().optional(),
  schedule_to: z.string().nullable().optional(),
  created_at: timestamp,
});

export const emailSchema = z.object({
  id,
  subject: z.string().nullable().optional(),
  reply: z.string().nullable().optional(),
  folders: z.any().nullable().optional(),
  created_at: timestamp,
});

/** Shared shape for the simple settings resources (source, type, group, tag, …). */
export const namedSettingSchema = z.object({
  id,
  name: z.string().min(1),
  created_at: timestamp,
});

export const roleSchema = z.object({
  id,
  name: z.string().min(1),
  description: z.string().nullable().optional(),
  permission_type: z.string().nullable().optional(),
  created_at: timestamp,
});

export const pipelineSchema = z.object({
  id,
  name: z.string().min(1),
  is_default: z.union([z.number(), z.boolean()]).nullable().optional(),
  stages: z.any().nullable().optional(),
});

export const attributeSchema = z.object({
  id,
  code: z.string().min(1),
  name: z.string().nullable().optional(),
  type: z.string().min(1),
  entity_type: z.string().nullable().optional(),
});

export const warehouseSchema = z.object({
  id,
  name: z.string().min(1),
  description: z.string().nullable().optional(),
  contact_address: z.any().nullable().optional(),
});

export const emailTemplateSchema = z.object({
  id,
  name: z.string().min(1),
  subject: z.string().nullable().optional(),
  content: z.string().nullable().optional(),
});

export const webhookSchema = z.object({
  id,
  name: z.string().min(1),
  end_point: z.string().nullable().optional(),
  method: z.string().nullable().optional(),
});

export const workflowSchema = z.object({
  id,
  name: z.string().min(1),
  entity_type: z.string().nullable().optional(),
  event: z.string().nullable().optional(),
});

export const webFormSchema = z.object({
  id,
  title: z.string().min(1),
  description: z.string().nullable().optional(),
});

export const campaignSchema = z.object({
  id,
  name: z.string().min(1),
  subject: z.string().nullable().optional(),
  status: z.union([z.number(), z.boolean()]).nullable().optional(),
});

export const eventSchema = z.object({
  id,
  name: z.string().min(1),
  description: z.string().nullable().optional(),
  date: z.string().nullable().optional(),
});
