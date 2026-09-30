import { ApiClient } from '../api/ApiClient';

type Task = () => Promise<unknown>;

/**
 * Per-test registry of resources to remove once the test finishes.
 *
 * Tasks run LIFO so children are deleted before the parents they hang off, and
 * a failing task never fails the suite — cleanup noise must not mask or invent
 * a functional failure.
 */
export class Cleanup {
  private readonly tasks: Task[] = [];

  constructor(private readonly client: ApiClient) {}

  /**
   * Register a REST resource for deletion, e.g. `track('leads', 42)`.
   */
  track(resource: string, id: number | string): void {
    const path = `/api/v1/${resource.replace(/^\/+|\/+$/g, '')}/${id}`;

    this.tasks.push(() => this.client.delete(path));
  }

  /**
   * Register an arbitrary teardown step.
   */
  add(task: Task): void {
    this.tasks.push(task);
  }

  get size(): number {
    return this.tasks.length;
  }

  async run(): Promise<void> {
    for (const task of this.tasks.reverse()) {
      try {
        await task();
      } catch (error) {
        console.warn(`[cleanup] skipped: ${(error as Error).message}`);
      }
    }

    this.tasks.length = 0;
  }
}
